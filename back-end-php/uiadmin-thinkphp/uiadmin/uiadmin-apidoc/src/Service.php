<?php

namespace uiadmin\apidoc;

use think\Route;

class Service extends \think\Service
{
    public function boot()
    {
        define("API_HOST", request()->root(true));

        $this->registerRoutes(function (Route $route) {
            if (config("uiadmin.apidoc.allow", true) != false) {
                // 分组
                $route->get('/apidoc/group$', function() {
                    $group = [];
                    $group[] = [
                        "name" => config("uiadmin.site.title") . '前台接口文档',
                        "basePath" => $_SERVER['HTTP_HOST'],
                        "url" => "/apidoc/doc.json",
                        "swaggerVersion" => "3.0"
                    ];
                    echo json_encode($group);
                })->ext('json');

                // 默认文档
                $route->get('/apidoc/doc$', function() {
                    $openapi = \OpenApi\scan([root_path() . 'extention/', root_path() . 'uiadmin/']);
                    header('Content-Type: application/json');
                    echo $openapi->toJson();
                })->ext('json');
            }
        });
    }
}
