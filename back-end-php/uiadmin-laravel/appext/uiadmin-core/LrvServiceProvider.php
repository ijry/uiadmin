<?php

namespace uiadmin\core;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use uiadmin\core\attributes\Index as RouteIndex;

// 实现DeferrableProvider时必须提供provides方法
// class LrvServiceProvider extends ServiceProvider implements DeferrableProvider
class LrvServiceProvider extends ServiceProvider
{

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    // public function provides()
    // {
    //     return [TestService::class];
    // }

    public function register()
    {
        if (env('UIADMIN_INSTALL')) {
            $service_list = get_ext_services();
            // foreach ($service_list as $key => $value) {
            //     $this->app->register($value);
            // }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!defined('EXT_DIR')) {
            // 1.2之前为extention，2.0之后改为appext
            define('EXT_DIR', 'appext');
        }

        // 路由
        Route::redirect('/' . config("uiadmin.xyadmin.entry") . '', request()->url(true) . '/');
        Route::get('/' . config("uiadmin.xyadmin.entry") . '/', function() {
            $secondsToCache = 3600;
            $ts = gmdate("D, d M Y H:i:s", time() + $secondsToCache) . " GMT";
            $ch= curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://uiadmin.jiangruyi.com/xyadmin/?version=' . get_config('xyadmin.version'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 表示不检查证书
            $xyadminIndex = curl_exec($ch);
            curl_close($ch);
            return $xyadminIndex;
        });

        // 根接口
        Route::middleware([\uiadmin\core\middleware\ResponseTransFormMiddleware::class])->group(function () {
            Route::get('/', "uiadmin\\core\\controller\\Core@index");
            Route::get('/admin/api', "uiadmin\\core\\admin\\Index@api");
            Route::post(config("uiadmin.site.apiPrefix") . '/v1/admin/core/user/login', "uiadmin\\core\\admin\\User@login");
            Route::get(config("uiadmin.site.apiPrefix") . '/v1/admin/core/index/index', "uiadmin\\core\\admin\\Index@index");
            Route::get(config("uiadmin.site.apiPrefix") . '/v1/admin/core/menu/trees', "uiadmin\\core\\admin\\Menu@trees");
            Route::get(config("uiadmin.site.apiPrefix") . '/v1/core/user/info', "uiadmin\\core\\controller\\User@info");
            Route::post(config("uiadmin.site.apiPrefix") . '/v1/core/upload/upload', "uiadmin\\core\\controller\\Upload@upload");
            Route::delete(config("uiadmin.site.apiPrefix") . '/v1/core/user/logout', "uiadmin\\core\\controller\\User@logout");

            // 注解菜单&路由
            RouteIndex::getMenuItems();
            // dump(\uiadmin\core\attributes\MenuItem::$all);
            $apiRootPath = config("uiadmin.site.apiPrefix");
            $dataList = \uiadmin\core\attributes\MenuItem::$all;
            foreach ($dataList as $key => $val) {
                if (\uiadmin\core\util\Str::startsWith($val['path'], '/')) {
                    $path = explode('/', $val['path']);
                    $nameRoot = '\\' . (explode('-', $val['module'])[0]) . '\\';
                    if (isset($path[3])) {
                        if ($val['menuLayer'] == 'home') {
                            // 前后端分离路由
                            Route::match(
                                explode('|', $val['apiMethod']),
                                $apiRootPath . '/' . $val['apiPrefix'] . $val['path'] . $val['apiSuffix'],
                                $nameRoot . $path[1] . '\controller\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3]
                            );
                            //->ext($val['apiExt'] ? : 'html|')
                            //->name($apiRootPath . '/' . $val['apiPrefix'] . '/' .  $path[1] . '/' . $path[2] .'/' . $path[3]);
                            // 前后端不分离路由
                            Route::match(
                                explode('|', $val['apiMethod']),
                                $val['path'] . $val['apiSuffix'],
                                $nameRoot . $path[1] . '\controller\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3]
                            );
                            //->ext($val['apiExt'] ? : 'html|')
                            //->name($path[1] . '/' . $path[2] .'/' . $path[3]); // name方法可以兼容tp5.1的多应用URL生成
                            // 自定义规则路由
                            if (isset($val['apiRule']) && $val['apiRule']) {
                                Route::match(
                                    explode('|', $val['apiMethod']),
                                    $val['apiRule'],
                                    $nameRoot . $path[1] . '\controller\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3]
                                );
                                //->ext($val['apiExt'] ? : 'html|')
                                //->name($path[1] . '/' . $path[2] .'/' . $path[3]); // name方法可以兼容tp5.1的多应用URL生成
                            }
                        } else if ($val['menuLayer'] == 'admin') {
                            Route::match(
                                explode('|', $val['apiMethod']),
                                $apiRootPath . '/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . $val['path'] . $val['apiSuffix'],
                                $nameRoot . $path[1] . '\\' . $val['menuLayer'] . '\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3]
                            );
                            //->name($apiRootPath . '/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . '/' . $path[1] . '/' . $path[2] .'/' . $path[3]);
                        } else {
                            // 前后端不分离路由
                            // Route::match(
                            //     explode('|', $val['apiMethod']),
                            //     '/'. $val['menuLayer'] . $val['path'] . $val['apiSuffix'],
                            //     $path[1] . '/' . $val['menuLayer'] . '.' . $path[2] . '/' . $path[3]
                            // );
                            // 前后端分离路由
                            Route::match(
                                explode('|', $val['apiMethod']),
                                $apiRootPath . '/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . $val['path'] . $val['apiSuffix'],
                                $nameRoot . $path[1] . '\controller\\' . $val['menuLayer'] . '\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3]
                            );
                            //->name($apiRootPath . '/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . '/' . $path[1] . '/' . $path[2] .'/' . $path[3]);
                        }
                    }
                }
            }
        });

        // 注册命令
        if ($this->app->runningInConsole()) {
            $this->commands([
                'uiadmin:publish' => \uiadmin\core\console\Publish::class,
                'uiadmin:install' => \uiadmin\core\console\Install::class
            ]);
        }
    }
}

