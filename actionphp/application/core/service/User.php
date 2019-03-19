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

namespace app\core\service;

use \Firebase\JWT\JWT; //导入JWT

class User
{
    private $core_login;

    public function __construct()
    {
        $this->core_login = new \app\core\model\Login();
    }

    /**
     * 获取用户列表
     * @return array
     *
     * @author jry <598821125@qq.com>
     */
    public function getList($map = [], $page = 1, $limit = 10) {
    }

    /**
     * 通过ID获取用户
     * @return array
     *
     * @author jry <598821125@qq.com>
     */
    public function getById($id) {
    }

    /**
     * 登录
     * @return mixed
     *
     * @author jry <598821125@qq.com>
     */
    public function login($user_info, $client = [])
    {
        // 颁发登录凭证token
        $key = \think\helper\Str::random(64); //秘钥
        $login_time = time();
        $expire_time = $login_time + 8640000; //100天有效期
        $token = [
            'iss' => 'initamin.net',//签发者
            'aud' => 'initamin.net',//面向的用户
            'iat' => $login_time,//签发时间
            'nbf' => $login_time,//在什么时候jwt开始生效
            'exp' => $expire_time,//token 过期时间
            'data'=>[
                'uid' => $user_info['id']//可以用户ID，可以自定义
            ]
        ]; //Payload
        $jwt = JWT::encode($token, $key); //此处行进加密算法生成jwt

        // 存进数据库
        $data = [];
        $data['uid'] = $user_info['id'];
        $data['key'] = $key;
        $data['token'] = $jwt;
        $data['login_time'] = $login_time;
        $data['expire_time'] = $expire_time;
        if ($client == []) {
            $client = ['type' => 0, 'name' => ''];
        }
        $data['client_type'] = $client['type'];
        $data['client_name'] = $client['name'];
        if ($this->core_login->insert($data)) {
            return $jwt;
        }
        return false;
    }
}
