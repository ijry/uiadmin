<?php

namespace uiadmin\core;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LrvService extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {echo 'asd';
        // $this->app->middleware->add(Router::class);

        // if (env('uiadmin.install')) {
        //     $service_list = get_ext_services();
        //     foreach ($service_list as $key => $value) {
        //         $this->app->register($value);
        //     }
        // }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {echo 'asd';
        if ($this->app->runningInConsole()) {
            // $this->commands([
            //     InstallCommand::class,
            //     PublishCommand::class,
            // ]);
        }

        if ($this->app->runningInConsole()) {
            // $this->publishes([
            //     __DIR__ . '/../runtimes' => $this->app->basePath('docker'),
            // ], ['sail', 'sail-docker']);

            // $this->publishes([
            //     __DIR__ . '/../bin/sail' => $this->app->basePath('sail'),
            // ], ['sail', 'sail-bin']);
        }

        // 分组
        Route::redirect('/' . config("uiadmin.xyadmin.entry") . '$', request()->url(true) . '/');
        Route::get('/' . config("uiadmin.xyadmin.entry") . '/$', function() {
            $secondsToCache = 3600;
            $ts = gmdate("D, d M Y H:i:s", time() + $secondsToCache) . " GMT";
            $ch= curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://admin.starideas.net/xyadmin/?version=' . get_config('xyadmin.version'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 表示不检查证书
            $xyadminIndex = curl_exec($ch);
            curl_close($ch);
            return $xyadminIndex;
        });

        // 根接口
        Route::get('/t$', "uiadmin\\core\\controller\\Core@index");
        Route::get('/admin/api$', "uiadmin\\core\\admin\\Index@api");
        Route::get(config("uiadmin.site.apiPrefix") . '/v1/admin/$', "uiadmin\\core\\admin\\Index@api");
        Route::post(config("uiadmin.site.apiPrefix") . '/v1/admin/core/user/login', "uiadmin\\core\\admin\\User@login");
        Route::get(config("uiadmin.site.apiPrefix") . '/v1/admin/core/index/index$', "uiadmin\\core\\admin\\Index@index");
        Route::get(config("uiadmin.site.apiPrefix") . '/v1/admin/core/menu/trees$', "uiadmin\\core\\admin\\Menu@trees");
        Route::get(config("uiadmin.site.apiPrefix") . '/v1/core/user/info$', "uiadmin\\core\\controller\\User@info");
        Route::delete(config("uiadmin.site.apiPrefix") . '/v1/core/user/logout', "uiadmin\\core\\controller\\User@logout");

        // 注册命令
        // $this->commands(['uiadmin:publish' => \uiadmin\core\command\Publish::class]);
        // $this->commands(['uiadmin:install' => \uiadmin\core\command\Install::class]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    // public function provides()
    // {
    //     // return [
    //     //     InstallCommand::class,
    //     //     PublishCommand::class,
    //     // ];
    // }
}

