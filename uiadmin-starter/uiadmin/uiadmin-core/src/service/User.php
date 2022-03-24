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

namespace uiadmin\core\service;

use think\Request;

/**
 * 用户服务
 *
 * @author jry <ijry@qq.com>
 */
class User
{
    // 登录
    public function login($account, $password) {
        $userLists = config('uiadmin.user.lists');
        foreach ($userLists as $key => $userInfo) {
            if ($userInfo['username'] == $account) {
                // 判断密码
                if ($password == $userInfo['password']) {
                    unset($userInfo['password']);
                    return $userInfo;
                } else {
                    throw new \Exception("密码错误", 0);
                }
            }
        }
        throw new \Exception("用户不存在", 0);
    }

    // 获取用户信息
    public function getById($uid) {
        $userLists = config('uiadmin.user.lists');
        foreach ($userLists as $key => $value) {
            if ($value['id'] == $uid) {
                return [
                    'id' => $value['id'],
                    'nickname' => $value['nickname'],
                    'username' => $value['username'],
                    'avatar' => $value['avatar'],
                    'roles' => $value['roles'],
                ];
            }
        }
        throw new \Exception("用户不存在", 0);
    }

    /**
     * 登录检测
     * @param  string $jwt Token
     * @return mixed
     *
     * @author jry <598821125@qq.com>
     */
    public function isLogin($token, $extra = [])
    {
        // jwt验证
        try {
            if (!$token) {
                throw new \Exception('请先登录', 401);
            }
            $tokenArray = explode(' ', $token);
            if (!isset($tokenArray[1])) {
                throw new \Exception('token格式错误', 401);
            }
            $jwt = $tokenArray[1]; // 签发的Token
            if (!$jwt) {
                throw new \Exception('未提交用户Token', 401);
            }

            // 解密
            \Firebase\JWT\JWT::$leeway = 60; // 当前时间减去60，把时间留点余地
            $decoded = \Firebase\JWT\JWT::decode($jwt, 'uiadmin', ['HS256']); // HS256方式，这里要和签发的时候对应
            $arr = (array)$decoded;
            $arr['data']->token = $jwt;

            // 只读模式
            return $arr;
        } catch (\Firebase\JWT\SignatureInvalidException $e) {  // 签名不正确
            throw new \Exception($e->getMessage(), 401);
        } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            throw new \Exception($e->getMessage(), 401);
        } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
            throw new \Exception($e->getMessage(), 401);
        } catch (\Exception $e) {  //其他错误
            throw new \Exception($e->getMessage(), 401);
        }
    }
}
