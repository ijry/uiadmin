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

            $userService = new \uniadmin\core\service\User();
            $userInfo = $userService->login($account, $password);

            $token = "Bearer " + session_id();
            // 可以开启tp6的session驱动解决分布式需求
        } catch (\Exception $e) {
            // 返回数据
            return json_encode([
                'code' => 0,
                'msg'  => $e->getMessage(),
                'data' => []
            ]);
        }
        

        // 返回数据
        return json_encode([
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
        // 先获取用户ID
        $userInfo = [
            'nickname' => '',
            'username' => '',
            'avatar'   => '',
            'roles'    => []
        ];

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
