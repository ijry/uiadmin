<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace uiadmin\core;

use App\Http\Controllers\Controller;

/**
 * 控制器基础类
 */
/**
 * 控制器基础类
 */
class BaseController extends Controller
{
    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    // protected function validate(array $data, $validate = [], array $message = [], bool $batch = false)
    // {
        // if (count($validate) == 0) {
        //     $validate = $this->validateRule;
        // }
        // if (count($message) == 0) {
        //     $message = $this->validateMessage;
        // }
        // if (is_array($validate)) {
        //     $v = new Validate();
        //     $v->rule($validate);
        // } else {
        //     if (strpos($validate, '.')) {
        //         // 支持场景
        //         [$validate, $scene] = explode('.', $validate);
        //     }
        //     $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
        //     $v     = new $class();
        //     if (!empty($scene)) {
        //         $v->scene($scene);
        //     }
        // }

        // $v->message($message);

        // // 是否批量验证
        // if ($batch || $this->batchValidate) {
        //     $v->batch(true);
        // }

        // try {
        //     $ret = $v->failException(true)->check($data);
        // } catch (\Exception $e) {
        //     return $this->return(['code' => 0, 'msg' => $e->getMessage(), 'data' => []]);
        // }
        // return $ret ;
    //}

    // 返回json
    protected function return($data) {
        return json($data);
    }

    /**
     * 判断用户是否登陆方法
     * @param  string $token
     * @return array
     * @author jry <ijry@qq.com>
     */
    protected function isLogin($redirect = 0)
    {
        try {
            // 获取token
            if (input('get.token', '')) {
                $token = input('get.token', '');
            } else {
                $token = \think\facade\Request::header('Authorization');
                if (!$token) {
                    $token = session('Authorization'); // 支持session
                    if (!$token) {
                        throw new \Exception("未登录", 402);
                    }
                }
            }
            $userService = new \uiadmin\core\service\User();
            $ret = $userService->isLogin($token, ['pathinfo' => request()->pathinfo()]);
            return ['code' => 200, 'msg' => '成功', 'data' => $ret];
        } catch (\Exception $e) {
            return ['code' => $e->getCode(), 'msg' => $e->getMessage(), 'data' => ['redirect' => $redirect]];
        }
    }

}
