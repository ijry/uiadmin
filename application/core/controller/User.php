<?php
/**
 * +----------------------------------------------------------------------
 * | InitAdmin/actionphp [ InitAdmin渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2019 http://initadmin.net All rights reserved.
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

use \Firebase\JWT\JWT; //导入JWT

class User extends Home
{
    private $core_user;
    private $core_identity;

    public function __construct()
    {
        $this->core_user = Db::name('core_user');
        $this->core_identity = Db::name('core_identity');
    }

    /**
     * 用户列表
     *
     * @return \think\Response
     */
    public function lists()
    {
        // 用户列表
        $user_list = $this->core_user
            ->where(['delete_time' => 0, 'status' => 1])
            ->select();
        dump($user_list);
    }

    /**
    * 是否登陆
    *
    * @return \think\Response
    */
    public function is_login()
    {
        $ret = $this->is_login();
        return json($ret);
    }

    /**
    * 获取用户信息
    *
    * @return \think\Response
    */
    public function info()
    {
        $ret = $this->is_login();
        if($ret['code'] != 200){
            return json($ret);
        }
        $user_info = $this->core_user->find($ret['data']['uid']);
        dump($user_info);
    }

    /**
     * 用户登录
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function login(Request $request)
    {
        //获取提交的账号密码/验证码
        $identity_type = input('post.identity_type')?:0;
        $identifier = input('post.identifier');
        $credential = input('post.credential');

        //数据验证

        //登录验证
        switch ($identity_type) {
        case 1: //手机号
            $map = [];
            $map['identity_type'] = $identity_type;
            $map['identifier'] = $identifier;
            $user_identity_info = $this->core_identity->where($map)->find();
            if (!$user_identity_info) {
                return json(['code' => 0, 'msg' => '手机号不存在']);
            }
            if ($user_identity_info['verified'] !== 1) {
                return json(['code' => 0, 'msg' => '手机号未通过验证']);
            }
            $user_info = $this->core_user->where(['id' => $user_identity_info['uid']])->find();
            if (!$user_info) {
                return json(['code' => 0, 'msg' => '用户不存在']);
            }
            if ($user_info['status'] !== 1) {
                return json(['code' => 0, 'msg' => '账号状态异常']);
            }
            break;
        case 2: //邮箱
            $map = [];
            $map['identity_type'] = $identity_type;
            $map['identifier'] = $identifier;
            $user_identity_info = $this->core_identity->where($map)->find();
            if (!$user_identity_info) {
                return json(['code' => 0, 'msg' => '邮箱不存在']);
            }
            if ($user_identity_info['verified'] !== 1) {
                return json(['code' => 0, 'msg' => '邮箱未通过验证']);
            }
            $user_info = $this->core_user->where(['id' => $user_identity_info['uid']])->find();
            if (!$user_info) {
                return json(['code' => 0, 'msg' => '用户不存在']);
            }
            if ($user_info['status'] !== 1) {
                return json(['code' => 0, 'msg' => '账号状态异常']);
            }
            break;
        default: //用户名
            $map = [];
            $map['username'] = $identifier;
            $user_info = $this->core_user->where($map)->find();
            if (!$user_info) {
                return json(['code' => 0, 'msg' => '用户名不存在']);
            }
            if ($user_info['status'] !== 1) {
                return json(['code' => 0, 'msg' => '账号状态异常']);
            }
            if ($user_info['password'] !== user_md5($credential)) {
                return json(['code' => 0, 'msg' => '密码错误']);
            }
            break;
        }
    
        //颁发登录凭证token
        $key = env('auth_key'); //秘钥加密关键 Signature
        $token = [
            'iss' => 'initamin.net',//签发者
            'aud' => 'initamin.net',//面向的用户
            'iat' => time(),//签发时间
            'nbf' => time(),//在什么时候jwt开始生效
            'exp' => time() + 720000,//token 过期时间
            'data'=>[
                'uid' => $user_info['id']//可以用户ID，可以自定义
            ]
        ]; //Payload
        $jwt = JWT::encode($token, $key); //此处行进加密算法生成jwt
        return json(['code' => 200, 'msg' => '登陆成功', 'data' => ['token' => $jwt]]);
    }
}
