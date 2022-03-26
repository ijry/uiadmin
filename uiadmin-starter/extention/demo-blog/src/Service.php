<?php

namespace demo\blog;

use think\Route;
use think\Validate;

class Service extends \think\Service
{
    public function register()
    {
    }

    public function boot()
    {
        $this->registerRoutes(function (Route $route) {
            // 在这里注册前端路由接口
            $route->get(config("uiadmin.site.apiPrefix") . '/v1/blog/post/info/:id', '\\demo\\blog\\controller\\Post@info');
        });
    }
}
