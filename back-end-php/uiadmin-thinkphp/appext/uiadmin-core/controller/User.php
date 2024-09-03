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

/**
 * 用户控制器
 * 
 * @OA\Tag(
 *     name="核心模块",
 *     description="包含用户登录、注销、详情、上传等基本核心接口",
 * )
 *
 * @author jry <ijry@qq.com>
 */
class User extends BaseHome
{
    /**
     * 获取用户信息
     * 
     * @OA\GET(
     *     tags={"核心模块"},
     *     summary="用户详情",
     *     description="获取当前登录用户的基本信息，包含头像、昵称等",
     *     path="/api/v1/core/user/info",
     *     @OA\Response(response="200",description="成功"),
     * )
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function info()
    {
        // 获取用户ID
        $class = config('uiadmin.user.driver');
        $userService = new $class();
        $login = $this->isLogin();
        $userInfo = $userService->getById($login['uid']);

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
     * @OA\DELETE(
     *     tags={"核心模块"},
     *     summary="用户注销",
     *     description="注销当前登录的账号",
     *     path="/api/v1/core/user/logout",
     *     @OA\Response(response="200",description="成功"),
     * )
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function logout()
    {
        // 修复注销
        session('Authorization', '');

        // 返回数据
        return json([
            'code' => 200,
            'msg'  => '成功',
            'data' => [
            ]
        ]);
    }
}
