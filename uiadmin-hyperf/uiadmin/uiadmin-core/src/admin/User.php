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

use Hyperf\HttpServer\Contract\RequestInterface;
use uiadmin\core\admin\BaseAdmin;

/**
 * 用户控制器
 *
 * @author jry <ijry@qq.com>
 */
class User extends BaseAdmin
{
    /**
     * 登录
     *
     * @return \Response
     * @author jry <ijry@qq.com>
     */
    public function login(RequestInterface $request)
    {
        try {
            $account = $request->input('account');
            $password = $request->input('password');

            $class = config('uiadmin.user.driver');
            $userService = new $class();
            $userInfo = $userService->login($account, $password);

            // 颁发登录凭证token
            $userkey = $userInfo['userKey']; // 秘钥
            $loginTime = time();
            $expireTime = $loginTime + 8640000; // 100天有效期
            $token = [
                'iss' => 'jiangruyi.com', // 签发者
                'aud' => 'jiangruyi.com', // 面向的用户
                'iat' => $loginTime, // 签发时间
                'nbf' => $loginTime, // 在什么时候jwt开始生效
                'exp' => $expireTime, // token过期时间
                'data'=>[
                    'uid' => $userInfo['id'] // 可以用户ID，可以自定义
                ]
            ]; //Payload
            $jwt = \Firebase\JWT\JWT::encode($token, $userkey); // 此处行进加密算法生成jwt

            session_start();
            $sessionId = session_id();
            session('userInfo', $userInfo);
            session('Authorization', 'Bearer ' . $jwt); // 支持session+jwt登录方式
            if (!$sessionId) {
                // 返回数据
                return json([
                    'code' => 0,
                    'msg'  => "获取sessionid失败",
                    'data' => []
                ]);
            }
            $token = "Bearer " . $jwt;
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
     * @return \Response
     * @author jry <ijry@qq.com>
     */
    public function info()
    {
        // 获取用户ID
        $login = $this->isLogin();
        $class = config('uiadmin.user.driver');
        $userService = new $class();
        $userInfo = $userService->getById($$login['uid']);

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
