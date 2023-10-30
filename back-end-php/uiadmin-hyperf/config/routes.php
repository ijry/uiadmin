<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;

Router::get('/favicon.ico', function () {
    return '';
});

// 加载uiadmin路由
include_once  BASE_PATH . '/uiadmin/uiadmin-auth/src/routes.php';
include_once  BASE_PATH . '/uiadmin/uiadmin-core/src/routes.php';
