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

namespace uniadmin\core\controller;

use think\Request;

/**
 * 用户控制器
 *
 * @author jry <ijry@qq.com>
 */
class User
{
    /**
     * 获取用户信息
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function info()
    {
        // 获取用户ID
        $class = config('uniadmin.user.driver');
        $userService = new $class();
        $userInfo = $userService->getById(session('userInfo.id'));

        // 返回数据
        return json_encode([
            'code' => 200,
            'msg'  => '成功',
            'data' => [
                'userInfo' => $userInfo
            ]
        ]);
    }
}
