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
}
