<?php

namespace uniadmin\core;

use think\Route;
use think\Service;
use think\Validate;

class MyService extends Service
{
    public function boot()
    {
        $this->registerRoutes(function (Route $route) {
            // 分组
            $route->get('/admin/$', function() {
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
            $route->get('/admin/api$', "uniadmin\\core\\admin\\Index@api");
            $route->get(config("uniadmin.site.apiPrefix") . '/v1/admin/$', "uniadmin\\core\\admin\\Index@api");
            $route->post(config("uniadmin.site.apiPrefix") . '/v1/admin/core/user/login', "uniadmin\\core\\admin\\User@login");
            $route->get(config("uniadmin.site.apiPrefix") . '/v1/admin/core/index/index$', "uniadmin\\core\\admin\\Index@index");
            $route->get(config("uniadmin.site.apiPrefix") . '/v1/admin/core/menu/trees$', "uniadmin\\core\\admin\\Menu@trees");
            $route->get(config("uniadmin.site.apiPrefix") . '/v1/core/user/info$', "uniadmin\\core\\controller\\User@info");
        });
    }
}