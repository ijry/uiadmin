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

use uiadmin\auth\model\User as UserModel;

/**
 * 用户服务
 *
 * @author jry <ijry@qq.com>
 */
class User
{
    // 登录
    public function login($account, $password) {
        if (!$account) {
            throw new \Exception("用户名不能为空", 0);
        }
        $info = UserModel::select(['id','user_key','nickname','username','avatar','roles','password','status'])
            ->where('username', $account)
            ->first();
        if (!$info) {
            throw new \Exception("用户名不存在", 0);
        }
        if ($info['password'] != user_pwd_md5($password, "uiadmin")) {
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
        $info = UserModel::select(['id','nickname','username','avatar','roles'])
            ->where('id', $uid)
            ->first();
        return $info;
    }
}
