<?php

namespace uiadmin\core;

use think\Route;
use think\Service;
use think\Validate;

class MyService extends Service
{
    public function register()
    {
        // $this->app->middleware->add(Router::class);
    }

    public function boot()
    {
        $this->registerRoutes(function (Route $route) {
            // 分组
            $route->redirect('/' . config("uiadmin.xyadmin.entry") . '$', request()->url(true) . '/');
            $route->get('/' . config("uiadmin.xyadmin.entry") . '/$', function() {
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
            $route->get('/admin/api$', "uiadmin\\core\\admin\\Index@api");
            $route->get(config("uiadmin.site.apiPrefix") . '/v1/admin/$', "uiadmin\\core\\admin\\Index@api");
            $route->post(config("uiadmin.site.apiPrefix") . '/v1/admin/core/user/login', "uiadmin\\core\\admin\\User@login");
            $route->get(config("uiadmin.site.apiPrefix") . '/v1/admin/core/index/index$', "uiadmin\\core\\admin\\Index@index");
            $route->get(config("uiadmin.site.apiPrefix") . '/v1/admin/core/menu/trees$', "uiadmin\\core\\admin\\Menu@trees");
            $route->get(config("uiadmin.site.apiPrefix") . '/v1/core/user/info$', "uiadmin\\core\\controller\\User@info");
        });

        // 注册命令
        $this->commands(['uiadmin:publish' => \uiadmin\core\command\Publish::class]);
        $this->commands(['uiadmin:install' => \uiadmin\core\command\Install::class]);
    }
}