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

namespace uiadmin\core\controller;

use think\Request;

/**
 * 用户控制器
 *
 * @author jry <ijry@qq.com>
 */
class User extends BaseHome
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
        $class = config('uiadmin.user.driver');
        $userService = new $class();
        $uid = session('userInfo.id');
        if (!$uid) {
            // 返回数据
            return json([
                'code' => 402,
                'msg'  => '登录过期',
                'data' => []
            ]);
        }
        $userInfo = $userService->getById($uid);

        // 返回数据
        return json([
            'code' => 200,
            'msg'  => '成功',
            'data' => [
                'userInfo' => $userInfo
            ]
        ]);
    }

    /**
     * 注销
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function logout()
    {
        // 返回数据
        return json([
            'code' => 200,
            'msg'  => '成功',
            'data' => [
            ]
        ]);
    }
}
