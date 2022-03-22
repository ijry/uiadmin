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

namespace uiadmin\auth\service;

use think\Request;
use think\facade\Db;

/**
 * 用户服务
 *
 * @author jry <ijry@qq.com>
 */
class User
{
    // 登录
    public function login($account, $password) {
        $info = Db::name('auth_user')
            ->filed('id,nickanme,username,avatar,roles,password')
            ->where('username', $account)
            ->findOrFail();
        if ($info['password'] != user_pwd_md5($password, "uniadmin")) {
            throw new \Exception("密码错误", 0);
        }
        if ($info['status'] == 0) {
            throw new \Exception("用户已被禁用", 0);
        }
        if ($info['status'] != 1) {
            throw new \Exception("用户状态异常", 0);
        }

        unset($info['password']);
        return $info;
    }

    // 获取用户信息
    public function getById($uid) {
        $info = Db::name('auth_user')
            ->filed('id,nickanme,username,avatar,roles')
            ->where('id', $uid)
            ->findOrFail();
        return $info;
    }
}
