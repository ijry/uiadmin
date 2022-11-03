<?php

namespace uiadmin\ext;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use uiadmin\config\model\Config as ConfigModel;

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
        // 一旦您的包的迁移被注册，它们将在执行 php artisan migrate 命令时自动运行。 
        // 您不需要将它们导出到应用程序的 database/migrations 目录。
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

