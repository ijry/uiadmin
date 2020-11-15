<?php
/**
 * +----------------------------------------------------------------------
 * | UniAdmin [ 渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2020 http://uniadmin.jiangruyi.com All rights reserved.
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
     * @author jry <ijry@qq.com>
     */
    public function getList($map = [], $page = 1, $limit = 10) {
    }

    /**
     * 通过ID获取用户
     * @return array
     *
     * @author jry <ijry@qq.com>
     */
    public function getById($id) {
        $userInfo = $this->core_user
            ->field('id,nickname,username,avatar')
            ->where('id', '=', $id)
            ->find();
        $userInfo['email'] = $this->core_identity
            ->field('identifier,verified')
            ->where('uid', '=', $userInfo['id'])
            ->where('identityType', '=', 2)
            ->find();
        $userInfo['mobile'] = $this->core_identity
            ->field('identifier,verified')
            ->where('uid', '=', $userInfo['id'])
            ->where('identityType', '=', 1)
            ->find();
        $userInfo['href'] = url("core/user/home", ["uid" => $userInfo["id"]]);
        return $userInfo;
    }

    /**
     * 登录
     * @return mixed
     *
     * @author jry <ijry@qq.com>
     */
    public function login($userInfo, $client = [])
    {
        // 颁发登录凭证token
        $key = \think\helper\Str::random(64); //秘钥
        $loginTime = time();
        $expireTime = $loginTime + 8640000; //100天有效期
        $token = [
            'iss' => 'uniadmin',//签发者
            'aud' => 'uniadmin',//面向的用户
            'iat' => $loginTime,//签发时间
            'nbf' => $loginTime,//在什么时候jwt开始生效
            'exp' => $expireTime,//token 过期时间
            'data'=>[
                'uid' => $userInfo['id']//可以用户ID，可以自定义
            ]
        ]; //Payload
        $jwt = JWT::encode($token, $key); //此处行进加密算法生成jwt

        // 存进数据库
        $data = [];
        $data['uid'] = $userInfo['id'];
        $data['key'] = $key;
        $data['token'] = $jwt;
        $data['loginTime'] = $loginTime;
        $data['expireTime'] = $expireTime;
        if ($client == []) {
            $client = ['type' => 0, 'name' => ''];
        }
        $data['clientType'] = $client['type'];
        $data['clientName'] = $client['name'];
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
     * @author jry <ijry@qq.com>
     */
    public function isLogin($token)
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
            if ($info['expireTime'] <= time()) {
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
