<?php
use Hyperf\HttpServer\Router\Router;
use uiadmin\auth\model\Menu as MenuModel;

// 这里是后台路由注册
if (env('UIADMIN_INSTALL')) {
    $dataList = MenuModel::where('status', '=' , 1)
        ->where('menu_layer', '=' , 'admin')
        ->whereIn('menu_type', [1,2,3])
        ->get();
    // var_dump($dataList);
    foreach ($dataList as $key => $val) {
        if (\uiadmin\core\util\Str::startsWith($val['path'], '/')) {
            $path = explode('/', $val['path']);
            if (isset($path[3])) {
                    $apiSuffix = explode('/:', $val->api_suffix);
                    $apiSuffixReal = '';
                    unset($apiSuffix[0]);
                    foreach ($apiSuffix as $key => $value) {
                        $apiSuffixReal = $apiSuffixReal . '{' . $value . '}';
                    }
                    if (count($apiSuffix) > 0) {
                        $apiSuffixReal = '/' . $apiSuffixReal;
                    }
                    //dump($apiSuffixReal);
                    // 前后端分离路由
                    Router::addRoute(
                        explode('|', $val['api_method']),
                        config("uiadmin.site.apiPrefix") . '/' . $val->api_prefix . '/admin' . $val['path'] . $apiSuffixReal,
                        $val['namespace'] . '\\' . $path[1] . '\admin\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3]);
            }
        }
    }
}