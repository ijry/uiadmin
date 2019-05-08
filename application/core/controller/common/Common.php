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

namespace app\core\controller\common;

use think\Db;
use think\Controller;
use think\facade\Request;
use \Firebase\JWT\JWT; //导入JWT

/**
 * 公共继承控制器
 *
 * @author jry <ijry@qq.com>
 */
class Common extends Controller
{
    protected function initialize()
    {
        if (is_file(env('root_path') . 'vendor/vendor/autoload.php')) {
            require_once env('root_path') . 'vendor/vendor/autoload.php';
        }
    }

    /**
     * 返回数据或者页面
     * @param  array $data
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    protected function return($data)
    {
        // 判断pathinfo中是否含有/api/v1/来判断时API请求还是页面请求
        if (\think\helper\Str::contains(request()->pathinfo(), '/api/v1/')) {
            return json($data);
        } else {
            $this->assign($data['data']);
            return $this->fetch();
        }
    }

    /**
     * 判断用户是否登陆方法
     * @param  string $token
     * @return array
     * @author jry <ijry@qq.com>
     */
    protected function isLogin()
    {
        //获取token
        $token = Request::header('Authorization');
        if (!$token) {
            return ['code' => 0, 'msg' => 'AuthorizationToken未提交'];
        }
        $jwt = explode(' ', $token)[1]; //签发的Token
        if (!$jwt) {
            return ['code' => 0, 'msg' => '未提交用户Token'];
        }

        //jwt验证
        try {
            //数据库验证
            $info = Db::name('core_login')
                ->removeOption('where')
                ->where('token', $jwt)
                ->find();
            if (!$info) {
                return ['code' => 0, 'msg' => 'token不存在', 'data' => ['need_login' => 1]];
            }
            if ($info['expire_time'] <= time()) {
                return ['code' => 0, 'msg' => '登录过期请重新登录', 'data' => ['need_login' => 1]];
            }

            //解密
            JWT::$leeway = 60;//当前时间减去60，把时间留点余地
            $decoded = JWT::decode($jwt, $info['key'], ['HS256']); //HS256方式，这里要和签发的时候对应
            $arr = (array)$decoded;
            if ($arr['data']->uid != $info['uid']) {
                return ['code' => 0, 'msg' => '数据异常请联系管理员', 'data' => ['need_login' => 1]];
            }
            $arr['data']->token = $jwt;
            return ['code' => 200, 'data' => $arr];
        } catch(\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            return ['code' => 0, 'msg' => $e->getMessage(), 'data' => ['need_login' => 1]];
        }catch(\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            return ['code' => 0, 'msg' => $e->getMessage(), 'data' => ['need_login' => 1]];
        }catch(\Firebase\JWT\ExpiredException $e) {  // token过期
            return ['code' => 0, 'msg' => $e->getMessage(), 'data' => ['need_login' => 1]];
        }catch(Exception $e) {  //其他错误
            return ['code' => 0, 'msg' => $e->getMessage(), 'data' => ['need_login' => 1]];
        }
    }
}
