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

use think\Model;
use \Firebase\JWT\JWT; //导入JWT

class User extends Model

{
    private $core_user;
    private $core_identity;
    private $core_login;

    public function __construct()
    {
        $this->core_user = new \app\core\model\User();
        $this->core_identity = new \app\core\model\Identity();
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
        $user_info = $this->core_user
            ->field('id,nickname,username,avatar')
            ->where('id', '=', $id)
            ->find();
        $user_info['email'] = $this->core_identity
            ->field('identifier,verified')
            ->where('uid', '=', $user_info['id'])
            ->where('identity_type', '=', 2)
            ->find();
        $user_info['mobile'] = $this->core_identity
            ->field('identifier,verified')
            ->where('uid', '=', $user_info['id'])
            ->where('identity_type', '=', 1)
            ->find();
        $user_info['href'] = url("core/user/home", ["uid" => $user_info["id"]]);
        return $user_info;
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
            session('Authorization', 'Bearer ' . $jwt); // 支持session+jwt登录方式
            return $jwt;
        }
        return false;
    }

    /**
     * 登录检测
     * @param  string $jwt Token
     * @return mixed
     *
     * @author jry <598821125@qq.com>
     */
    public function is_login($token)
    {
        // jwt验证
        try {
            if (!$token) {
                throw new \Exception('请先登录', 401);
            }
            $token_array = explode(' ', $token);
            if (!isset($token_array[1])) {
                throw new \Exception('token格式错误', 401);
            }
            $jwt = $token_array[1]; // 签发的Token
            if (!$jwt) {
                throw new \Exception('未提交用户Token', 401);
            }

            // 数据库验证
            $info = \think\Db::name('core_login')
                ->removeOption('where')
                ->where('token', $jwt)
                ->find();
            if (!$info) {
                throw new \Exception('token不存在', 401);
            }
            if ($info['expire_time'] <= time()) {
                throw new \Exception('登录过期请重新登录', 401);
            }

            //解密
            JWT::$leeway = 60; // 当前时间减去60，把时间留点余地
            $decoded = JWT::decode($jwt, $info['key'], ['HS256']); // HS256方式，这里要和签发的时候对应
            $arr = (array)$decoded;
            if ($arr['data']->uid != $info['uid']) {
                throw new \Exception('数据异常请联系管理员', 401);
            }
            $arr['data']->token = $jwt;
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
