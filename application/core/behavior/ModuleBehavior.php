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

class ModuleBehavior
{
    // 行为逻辑
    public function run($params)
    {
        // 计算路由
        $data_list = Db::name('core_menu')
            ->where('menu_type', '>' , 0)
            ->select();
        foreach ($data_list as $key => $val) {
            $path = explode('/', $val['path']);
            Route::rule(
                'api/' . $val['api_prefix'] . '/admin' . $val['path'] . $val['api_suffix'],
                $path[1] . '/admin.' . $path[2] . '/' . $path[3],
                $val['api_method']
            );
        }
    }
}