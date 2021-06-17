<?php
/**
 * +----------------------------------------------------------------------
 * | uniadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2021 http://uniadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/

namespace uniadmin\auth\admin;

use think\Request;

/**
 * 菜单控制器
 *
 * @author jry <ijry@qq.com>
 */
class Menu
{
    // 获取菜单
    public function trees() {
        $class = config('uniadmin.menu.driver');
        $service = new $class();
        $dataList = $service->getByUser(session('userInfo.roles'));

        // 返回数据
        return json([
            'code' => 200,
            'msg'  => '成功',
            'data' => [
                'listData' => [
                    'dataList' => $dataList
                ],
                'menu2routes' => true
            ]
        ]);
    }
}
