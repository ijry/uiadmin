<?php
/**
 * +----------------------------------------------------------------------
 * | InitAdmin/actionphp [ InitAdmin渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2019 http://initadmin.net All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <ijry@qq.com>
 * +----------------------------------------------------------------------
*/

namespace app\core\behavior;

use think\Db;
use think\facade\Route;
use think\facade\Request;

/**
 * 路由行为扩展
 *
 * @author jry <ijry@qq.com>
 */
class Router
{
    // 行为逻辑
    public function run($params)
    {
        // 内置路由
        Route::rule('/', 'cms/post/index'); // 首页访问路由
        Route::rule('/api/', 'core/index/api'); // API访问路由

        // 计算后台API路由
        $data_list = Db::name('core_menu')
            ->removeOption('where')
            ->where('menu_type', 'in' , '1,2,3')
            ->select();
        foreach ($data_list as $key => $val) {
            $path = explode('/', $val['path']);
            // 前后端分离路由
            Route::rule(
                'api/' . $val['api_prefix'] . '/admin' . $val['path'] . $val['api_suffix'],
                $path[1] . '/admin.' . $path[2] . '/' . $path[3],
                $val['api_method']
            );
            // 前后端不分离路由
            Route::rule(
                '/admin' . $val['path'] . $val['api_suffix'],
                $path[1] . '/admin.' . $path[2] . '/' . $path[3],
                $val['api_method']
            );
        }

        // 计算前台API路由
        $data_list = Db::name('core_menu')
            ->removeOption('where')
            ->where('menu_type', '=' , '5')
            ->select();
        foreach ($data_list as $key => $val) {
            $path = explode('/', $val['path']);
            // 前后端分离路由
            Route::rule(
                'api/' . $val['api_prefix'] . $val['path'] . $val['api_suffix'],
                $path[1] . '/' . $path[2] . '/' . $path[3],
                $val['api_method']
            );
            // 前后端不分离路由
            Route::rule(
                $val['path'] . $val['api_suffix'],
                $path[1] . '/' . $path[2] . '/' . $path[3],
                $val['api_method']
            );
        }

        // 模板标签
        config('template.taglib_pre_load', 'app\cms\taglib\Cms');
    }
}
