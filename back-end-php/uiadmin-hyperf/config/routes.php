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
use uiadmin\core\attributes\Index as RouteIndex;

//Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});
Router::get('/t', 'uiadmin\core\controller\Core@index');

// 注解菜单&路由
RouteIndex::getMenuItems();
// dump(\uiadmin\core\attributes\MenuItem::$all);
$apiRootPath = config("uiadmin.site.apiPrefix");
$dataList = \uiadmin\core\attributes\MenuItem::$all;
foreach ($dataList as $key => $val) {
    if (\uiadmin\core\util\Str::startsWith($val['path'], '/')) {
        $path = explode('/', $val['path']);
        $nameRoot = '\\' . (explode('-', $val['module'])[0]) . '\\';
        if (isset($path[3])) {
            if ($val['menuLayer'] == 'home') {
                // 前后端分离路由
                Router::rule(
                    $apiRootPath . '/' . $val['apiPrefix'] . $val['path'] . $val['apiSuffix'],
                    $nameRoot . $path[1] . '\controller\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3],
                    $val['apiMethod']
                );
                //->ext($val['apiExt'] ? : 'html|')
                //->name($apiRootPath . '/' . $val['apiPrefix'] . '/' .  $path[1] . '/' . $path[2] .'/' . $path[3]);
                // 前后端不分离路由
                Router::rule(
                    $val['path'] . $val['apiSuffix'],
                    $nameRoot . $path[1] . '\controller\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3],
                    $val['apiMethod']
                );
                //->ext($val['apiExt'] ? : 'html|')
                //->name($path[1] . '/' . $path[2] .'/' . $path[3]); // name方法可以兼容tp5.1的多应用URL生成
                // 自定义规则路由
                if (isset($val['apiRule']) && $val['apiRule']) {
                    Router::rule(
                        $val['apiRule'],
                        $nameRoot . $path[1] . '\controller\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3],
                        $val['apiMethod']
                    );
                    //->ext($val['apiExt'] ? : 'html|')
                    //->name($path[1] . '/' . $path[2] .'/' . $path[3]); // name方法可以兼容tp5.1的多应用URL生成
                }
            } else if ($val['menuLayer'] == 'admin') {
                Router::rule(
                    $apiRootPath . '/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . $val['path'] . $val['apiSuffix'],
                    $nameRoot . $path[1] . '\\' . $val['menuLayer'] . '\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3],
                    $val['apiMethod']
                );
                //->name($apiRootPath . '/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . '/' . $path[1] . '/' . $path[2] .'/' . $path[3]);
            } else {
                // 前后端不分离路由
                // Router::(
                //     '/'. $val['menuLayer'] . $val['path'] . $val['apiSuffix'],
                //     $path[1] . '/' . $val['menuLayer'] . '.' . $path[2] . '/' . $path[3],
                //     $val['apiMethod']
                // );
                // 前后端分离路由
                Router::rule(
                    $apiRootPath . '/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . $val['path'] . $val['apiSuffix'],
                    $nameRoot . $path[1] . '\controller\\' . $val['menuLayer'] . '\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3],
                    $val['apiMethod']
                );
                //->name($apiRootPath . '/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . '/' . $path[1] . '/' . $path[2] .'/' . $path[3]);
            }
        }
    }
}

