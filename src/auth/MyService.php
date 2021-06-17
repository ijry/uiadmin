<?php

namespace uniadmin\auth;

use think\Route;
use think\Service;
use think\Validate;

class MyService extends Service
{
    public function boot()
    {
        $this->registerRoutes(function (Route $route) {
            $route->get(config("uniadmin.site.apiPrefix") . '/v1/admin/auth/user/lists', "uniadmin\\auth\\admin\\User@lists");
            $route->post(config("uniadmin.site.apiPrefix") . '/v1/admin/auth/user/add', "uniadmin\\auth\\admin\\User@add");
            $route->put(config("uniadmin.site.apiPrefix") . '/v1/admin/auth/user/edit/:id', "uniadmin\\auth\\admin\\User@edit");
            $route->delete(config("uniadmin.site.apiPrefix") . '/v1/admin/auth/user/delete/:id', "uniadmin\\auth\\admin\\User@delete");
            $route->get(config("uniadmin.site.apiPrefix") . '/v1/core/auth/user/info', "uniadmin\\auth\\admin\\User@info");
        });
    }
}