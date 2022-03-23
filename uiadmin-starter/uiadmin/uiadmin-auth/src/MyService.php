<?php

namespace uiadmin\auth;

use think\Route;
use think\Service;
use think\Validate;

class MyService extends Service
{
    public function boot()
    {
        // 设置驱动为auth
        config('uiadmin.user.driver', 'uiadmin\\auth\\service\\User');
        config('uiadmin.menu.driver', 'uiadmin\\auth\\service\\Menu');


        $this->registerRoutes(function (Route $route) {
            $route->get(config("uiadmin.site.apiPrefix") . '/v1/admin/auth/user/lists', "uniadmin\\auth\\admin\\User@lists");
            $route->post(config("uiadmin.site.apiPrefix") . '/v1/admin/auth/user/add', "uniadmin\\auth\\admin\\User@add");
            $route->put(config("uiadmin.site.apiPrefix") . '/v1/admin/auth/user/edit/:id', "uniadmin\\auth\\admin\\User@edit");
            $route->delete(config("uiadmin.site.apiPrefix") . '/v1/admin/auth/user/delete/:id', "uniadmin\\auth\\admin\\User@delete");
            $route->get(config("uiadmin.site.apiPrefix") . '/v1/core/auth/user/info', "uniadmin\\auth\\admin\\User@info");
        });
    }
}