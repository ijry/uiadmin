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

/**
 * 用户控制器
 *
 * @author jry <ijry@qq.com>
 */
class User
{
    /**
     * 登录
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function login()
    {
        try {
            $account = input('post.account');
            $password = input('post.password');

            $class = config('uiadmin.user.driver');
            $userService = new $class();
            $userInfo = $userService->login($account, $password);

            session_start();
            $sessionId = session_id();
            session('userInfo', $userInfo);
            if (!$sessionId) {
                // 返回数据
                return json([
                    'code' => 0,
                    'msg'  => "获取sessionid失败",
                    'data' => []
                ]);
            }
            $token = "Bearer " . $sessionId;
            // 可以开启tp6的session驱动解决分布式需求
        } catch (\Exception $e) {
            // 返回数据
            return json([
                'code' => 0,
                'msg'  => $e->getMessage(),
                'data' => []
            ]);
        }
        

        // 返回数据
        return json([
            'code' => 200,
            'msg'  => '成功',
            'data' => [
                'token'    => $token,
                'userInfo' => $userInfo
            ]
        ]);
    }

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
        $userInfo = $userService->getById(session('userInfo.id'));

        // 返回数据
        return json([
            'code' => 200,
            'msg'  => '成功',
            'data' => [
                'userInfo' => $userInfo
            ]
        ]);
    }
}
