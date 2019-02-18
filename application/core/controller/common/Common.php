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

use think\Controller;
use think\facade\Request;
use \Firebase\JWT\JWT; //导入JWT

class Common extends Controller
{
  /**
  * 判断用户是否登陆方法
  * @param  string $token
  * @return array
  * @author jry <598821125@qq.com>
  */
  protected function is_login()
  {
      $key = env('auth_key'); //key要和签发的时候一样
      $token = Request::header('Authorization');
      if (!$token) {
        return ['code' => 0, 'msg' => 'AuthorizationToken not found'];
      }
      $jwt = explode(' ', $token)[1]; //签发的Token
      try {
          JWT::$leeway = 60;//当前时间减去60，把时间留点余地
          $decoded = JWT::decode($jwt, $key, ['HS256']); //HS256方式，这里要和签发的时候对应
          $arr = (array)$decoded;
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
