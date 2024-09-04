<?php
use Hyperf\HttpServer\Router\Router;
use uiadmin\core\attributes\Index as RouteIndex;
use Hyperf\HttpServer\Contract\ResponseInterface;

// 路由
// Router::redirect(, request()->url(true) . '/');
Router::get('/' . config("uiadmin.xyadmin.entry"), function(ResponseInterface $response) {
    return $response->redirect('/' . config("uiadmin.xyadmin.entry") . '/');
});
Router::get('/' . config("uiadmin.xyadmin.entry") . '/', function(ResponseInterface $response) {
    $secondsToCache = 3600;
    $ts = gmdate("D, d M Y H:i:s", time() + $secondsToCache) . " GMT";
    $ch= curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://uiadmin.jiangruyi.com/xyadmin/?version=' . get_config('xyadmin.version'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 表示不检查证书
    $xyadminIndex = curl_exec($ch);
    curl_close($ch);
    return $response->html($xyadminIndex);
});

// 根接口
//Router::addGroup('/', function () {
    Router::get('/', 'uiadmin\core\controller\Core@index');
    Router::get('/admin/api', "uiadmin\\core\\admin\\Index@api");
    Router::addRoute(["POST", "GET"], config("uiadmin.site.apiPrefix") . '/v1/admin/core/user/login', "uiadmin\\core\\admin\\User@login");
    Router::get(config("uiadmin.site.apiPrefix") . '/v1/admin/core/index/index', "uiadmin\\core\\admin\\Index@index");
    Router::get(config("uiadmin.site.apiPrefix") . '/v1/admin/core/menu/trees', "uiadmin\\core\\admin\\Menu@trees");
    Router::get(config("uiadmin.site.apiPrefix") . '/v1/core/user/info', "uiadmin\\core\\controller\\User@info");
    Router::post(config("uiadmin.site.apiPrefix") . '/v1/core/upload/upload', "uiadmin\\core\\controller\\Upload@upload");
    Router::delete(config("uiadmin.site.apiPrefix") . '/v1/core/user/logout', "uiadmin\\core\\controller\\User@logout");
//});

// 注解菜单&路由
RouteIndex::getMenuItems();
// var_dump(\uiadmin\core\attributes\MenuItem::$all);
$apiRootPath = config("uiadmin.site.apiPrefix");
$dataList = \uiadmin\core\attributes\MenuItem::$all;
foreach ($dataList as $key => $val) {
    if (\uiadmin\core\util\Str::startsWith($val['path'], '/')) {
        $path = explode('/', $val['path']);
        $nameRoot = '\\' . (explode('-', $val['module'])[0]) . '\\';
        if (isset($path[3])) {
            if ($val['menuLayer'] == 'home') {
                // 前后端分离路由
                Router::addRoute(
                    explode('|', $val['apiMethod']),
                    $apiRootPath . '/' . $val['apiPrefix'] . $val['path'] . $val['apiSuffix'],
                    $nameRoot . $path[1] . '\controller\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3]
                );
                //->ext($val['apiExt'] ? : 'html|')
                //->name($apiRootPath . '/' . $val['apiPrefix'] . '/' .  $path[1] . '/' . $path[2] .'/' . $path[3]);
                // 前后端不分离路由
                Router::addRoute(
                    explode('|', $val['apiMethod']),
                    $val['path'] . $val['apiSuffix'],
                    $nameRoot . $path[1] . '\controller\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3]
                );
                //->ext($val['apiExt'] ? : 'html|')
                //->name($path[1] . '/' . $path[2] .'/' . $path[3]); // name方法可以兼容tp5.1的多应用URL生成
                // 自定义规则路由
                if (isset($val['apiRule']) && $val['apiRule']) {
                    Router::addRoute(
                        explode('|', $val['apiMethod']),
                        $val['apiRule'],
                        $nameRoot . $path[1] . '\controller\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3]
                    );
                    //->ext($val['apiExt'] ? : 'html|')
                    //->name($path[1] . '/' . $path[2] .'/' . $path[3]); // name方法可以兼容tp5.1的多应用URL生成
                }
            } else if ($val['menuLayer'] == 'admin') {
                Router::addRoute(
                    explode('|', $val['apiMethod']),
                    $apiRootPath . '/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . $val['path'] . $val['apiSuffix'],
                    $nameRoot . $path[1] . '\\' . $val['menuLayer'] . '\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3]
                );
                //->name($apiRootPath . '/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . '/' . $path[1] . '/' . $path[2] .'/' . $path[3]);
            } else {
                // 前后端不分离路由
                // Router::addRoute(
                //     explode('|', $val['apiMethod']),
                //     '/'. $val['menuLayer'] . $val['path'] . $val['apiSuffix'],
                //     $path[1] . '/' . $val['menuLayer'] . '.' . $path[2] . '/' . $path[3]
                // );
                // 前后端分离路由
                Router::addRoute(
                    explode('|', $val['apiMethod']),
                    $apiRootPath . '/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . $val['path'] . $val['apiSuffix'],
                    $nameRoot . $path[1] . '\controller\\' . $val['menuLayer'] . '\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3]
                );
                //->name($apiRootPath . '/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . '/' . $path[1] . '/' . $path[2] .'/' . $path[3]);
            }
        }
    }
}

// $ret = Router::getData();
// var_dump($ret);
