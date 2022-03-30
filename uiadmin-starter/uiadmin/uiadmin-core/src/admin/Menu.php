<?php
/**
 * +----------------------------------------------------------------------
 * | uiadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/

namespace uiadmin\core\admin;

use think\Request;
use uiadmin\core\admin\BaseAdmin;

/**
 * 菜单控制器
 *
 * @author jry <ijry@qq.com>
 */
class Menu extends BaseAdmin
{
    // 获取菜单
    public function trees() {
        $class = config('uiadmin.user.driver');
        $userService = new $class();
        $login = $this->isLogin();
        $userInfo = $userService->getById($login['uid']);

        $class = config('uiadmin.menu.driver');
        $service = new $class();
        $dataList = $service->getByUser($userInfo['roles']);

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
