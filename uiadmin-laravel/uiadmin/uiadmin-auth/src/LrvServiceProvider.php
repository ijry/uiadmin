<?php

namespace uiadmin\auth;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use uiadmin\auth\model\Menu as MenuModel;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 安装auth扩展后设置驱动为auth
        $uiadmin_config = config('uiadmin');
        $uiadmin_config['user']['driver'] = 'uiadmin\\auth\\service\\User';
        $uiadmin_config['menu']['driver'] = 'uiadmin\\auth\\service\\Menu';
        config($uiadmin_config , 'uiadmin');

        // 路由
        if (env('UIADMIN_INSTALL')) {
            // 计算API路由
            $dataList = MenuModel::where('status', '=' , 1)
                ->where('menu_layer', '=' , 'admin')
                ->whereIn('menu_type', [1,2,3])
                ->get();
            foreach ($dataList as $key => $val) {
                if (\uiadmin\core\util\Str::startsWith($val['path'], '/')) {
                    $path = explode('/', $val['path']);
                    if (isset($path[3])) {
                            $apiSuffix = explode('/:', $val->api_suffix);
                            $apiSuffixReal = '';
                            unset($apiSuffix[0]);
                            foreach ($apiSuffix as $key => $value) {
                                $apiSuffixReal = $apiSuffixReal . '{' . $value . '}';
                            }
                            if (count($apiSuffix) > 0) {
                                $apiSuffixReal = '/' . $apiSuffixReal;
                            }
                            //dump($apiSuffixReal);
                            // 前后端分离路由
                            Route::match(
                                explode('|', $val['api_method']),
                                config("uiadmin.site.apiPrefix") . '/' . $val->api_prefix . '/admin' . $val['path'] . $apiSuffixReal,
                                $val['namespace'] . '\\' . $path[1] . '\admin\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3]
                            )->middleware(\uiadmin\core\middleware\ResponseTransFormMiddleware::class);
                    }
                }
            }
        }
    }
}

