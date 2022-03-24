<?php

namespace uiadmin\auth;

use think\Route;
use think\Service;
use think\Validate;

class MyService extends Service
{
    public function boot()
    {
        // 安装auth扩展后设置驱动为auth
        $uiadmin_config = config('uiadmin');
        $uiadmin_config['user']['driver'] = 'uiadmin\\auth\\service\\User';
        $uiadmin_config['menu']['driver'] = 'uiadmin\\auth\\service\\Menu';
        config($uiadmin_config , 'uiadmin');

        $this->registerRoutes(function (Route $route) {
            $route->get(config("uiadmin.site.apiPrefix") . '/v1/admin/auth/user/lists', "uniadmin\\auth\\admin\\User@lists");
            $route->post(config("uiadmin.site.apiPrefix") . '/v1/admin/auth/user/add', "uniadmin\\auth\\admin\\User@add");
            $route->put(config("uiadmin.site.apiPrefix") . '/v1/admin/auth/user/edit/:id', "uniadmin\\auth\\admin\\User@edit");
            $route->delete(config("uiadmin.site.apiPrefix") . '/v1/admin/auth/user/delete/:id', "uniadmin\\auth\\admin\\User@delete");
            $route->get(config("uiadmin.site.apiPrefix") . '/v1/core/auth/user/info', "uniadmin\\auth\\admin\\User@info");
        });
    }
}