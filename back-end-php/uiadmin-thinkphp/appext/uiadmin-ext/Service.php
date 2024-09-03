<?php

namespace uiadmin\ext;

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
        });
    }
}