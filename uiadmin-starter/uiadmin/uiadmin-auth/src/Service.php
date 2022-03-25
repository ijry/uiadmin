<?php

namespace uiadmin\auth;

use think\Route;
use think\Validate;
use uiadmin\auth\model\Menu as MenuModel;

class Service extends \think\Service
{
    public function boot()
    {
        // 安装auth扩展后设置驱动为auth
        $uiadmin_config = config('uiadmin');
        $uiadmin_config['user']['driver'] = 'uiadmin\\auth\\service\\User';
        $uiadmin_config['menu']['driver'] = 'uiadmin\\auth\\service\\Menu';
        config($uiadmin_config , 'uiadmin');

        $this->registerRoutes(function (Route $route) {
            $route->get(config("uiadmin.site.apiPrefix") . '/v1/core/auth/user/info', "uiadmin\\auth\\admin\\User@info");

            // 计算API路由
            $dataList = MenuModel::where('status', '=' , 1)
                ->where('menu_layer', 'in' , 'admin')
                ->where('menu_type', 'in' , '1,2,3')
                ->select();
            foreach ($dataList as $key => $val) {
                if (\uiadmin\core\util\Str::startsWith($val['path'], '/')) {
                    $path = explode('/', $val['path']);
                    if (isset($path[3])) {
                            // 前后端分离路由
                            $route->rule(
                                config("uiadmin.site.apiPrefix") . '/' . $val['apiPrefix'] . '/admin' . $val['path'] . $val['apiSuffix'],
                                $val['namespace'] . '\\' . $path[1] . '\admin\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3],
                                $val['apiMethod']
                            )->ext($val['apiExt'] ? : 'html|')
                            ->name(config("uiadmin.site.apiPrefix") . '/' . $val['apiPrefix'] . '/admin/' . $path[1] . '/' . $path[2] .'/' . $path[3]);
                    }
                }
            }

            // dump($route->getRuleList());exit;
        });
    }
}