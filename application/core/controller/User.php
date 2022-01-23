<?php
/**
 * +----------------------------------------------------------------------
 * | UiAdmin [ 渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <ijry@qq.com>
 * +----------------------------------------------------------------------
*/

namespace app\core\controller;

use think\Db;
use think\Request;
use app\core\controller\common\Home;

/**
 * 用户控制器
 *
 * @author jry <ijry@qq.com>
 */
class User extends Home
{
    private $core_user;
    private $core_identity;
    private $core_login;

    public function initialize()
    {
        parent::initialize();
        $this->core_user = new \app\core\model\User();
        $this->core_identity = new \app\core\model\Identity();
        $this->core_login = new \app\core\model\Login();
    }

    /**
     * 是否登陆
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function isLogin($redirect = 0)
    {
        $login = parent::isLogin();
        return $this->return(['code' => 200, 'msg' => '已登录系统', 'data' => ['uid' => $login['uid']]]);
    }

    /**
     * 获取用户信息
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function info()
    {
        $login = parent::isLogin();
        try {
            $user_service = new \app\core\service\User();
            $userInfo = $user_service->getById($login['uid']);
            return $this->return(['code' => 200, 'msg' => '用户信息', 'data' => ['userInfo' => $userInfo]]);
        } catch(\Exception $e) {
            return $this->return(['code' => 0, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * 用户登录
     *
     * @param  \think\Request  $request
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function login(Request $request)
    {
        if (request()->isPost()) {
            // 获取提交的账号密码
            $account = trim(input('post.account'));
            $password = input('post.password');

            // 数据验证
            if (!$account) {
                return $this->return(['code' => 0, 'msg' => '请输入账号']);
            }
            if (!$password) {
                return $this->return(['code' => 0, 'msg' => '请输入密码']);
            }

            //匹配登录方式
            if (preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $account)) {// 邮箱登录
                $map = [];
                $map['identityType'] = 2;
                $map['identifier'] = $account;
                $userIdentityInfo = $this->core_identity
                    ->where($map)
                    ->find();
                if (!$userIdentityInfo) {
                    return $this->return(['code' => 0, 'msg' => '邮箱不存在']);
                }
                if ($userIdentityInfo['verified'] !== 1) {
                    return $this->return(['code' => 0, 'msg' => '邮箱未通过验证']);
                }
                $userInfo = $this->core_user
                    ->where(['id' => $userIdentityInfo['uid']])
                    ->find();
                if (!$userInfo) {
                    return $this->return(['code' => 0, 'msg' => '用户不存在']);
                }
                if ($userInfo['status'] !== 1) {
                    return $this->return(['code' => 0, 'msg' => '账号状态异常']);
                }
            } elseif (preg_match("/^1\d{10}$/", $account)) { // 手机号登录
                $map = [];
                $map['identityType'] = 1;
                $map['identifier'] = $account;
                $userIdentityInfo = $this->core_identity
                    ->where($map)
                    ->find();
                if (!$userIdentityInfo) {
                    return $this->return(['code' => 0, 'msg' => '手机号不存在']);
                }
                if ($userIdentityInfo['verified'] !== 1) {
                    return $this->return(['code' => 0, 'msg' => '手机号未通过验证']);
                }
                $userInfo = $this->core_user->where(['id' => $userIdentityInfo['uid']])->find();
                if (!$userInfo) {
                    return $this->return(['code' => 0, 'msg' => '用户不存在']);
                }
                if ($userInfo['status'] !== 1) {
                    return $this->return(['code' => 0, 'msg' => '账号状态异常']);
                }
            } else { // 用户名登录
                $map = [];
                $map['username'] = $account;
                $userInfo = $this->core_user
                    ->where($map)
                    ->find();
                if (!$userInfo) {
                    return $this->return(['code' => 0, 'msg' => '用户名不存在']);
                }
                if ($userInfo['status'] !== 1) {
                    return $this->return(['code' => 0, 'msg' => '账号状态异常']);
                }
            }
            if ($userInfo['password'] !== user_md5($password, $userInfo['key'])) {
                return $this->return(['code' => 0, 'msg' => '密码错误']);
            }

            // 记录登录状态
            try {
                $user_service = new \app\core\service\User();
                $jwt = $user_service->login($userInfo, input('post.client'));
            } catch(\Exception $e) {
                return $this->return(['code' => 0, 'msg' => '登录失败:' . $e->getMessage()]);
            }
            if ($jwt) {
                unset($userInfo['key']);
                unset($userInfo['password']);
                return $this->return(['code' => 200, 'msg' => '登陆成功', 'data' => [
                    'token' => 'Bearer ' . $jwt,
                    'userInfo' => $userInfo
                ]]);
            } else {
                return $this->return(['code' => 0, 'msg' => '添加失败', 'data' => []]);
            }
        } else {
            return $this->return(['code' => 200, 'msg' => '成功', 'data' => []]);
        }
    }

    /**
     * 注销登录
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function logout()
    {
        $login = parent::isLogin();
        $ret = $this->core_login
            ->where('token', $login['token'])
            ->delete(true);
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '注销成功', 'data' => []]);
        } else {
            return $this->return(['code' => 0, 'msg' => '注销失败', 'data' => []]);
        }
    }
}
