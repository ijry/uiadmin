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

use think\App;
use think\exception\ValidateException;
use think\Validate;

/**
 * 控制器基础类
 */
/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 租户ID
    protected $cloudId = 0;
    // 子站
    protected $eid = 0;

    // 初始化
    protected function initialize()
    {
        request()->cloudId = 0;
        $this->cloudId = request()->cloudId;
    }

    protected $validateRule = [];
    protected $validateMessage = [];

    // 验证
    protected function validateMake($validateRule, $validateMessage) {
        $this->validateRule =  $validateRule;
        $this->validateMessage =  $validateMessage;
    }

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
    protected function validate(array $data, $validate = [], array $message = [], bool $batch = false)
    {
        if (count($validate) == 0) {
            $validate = $this->validateRule;
        }
        if (count($message) == 0) {
            $message = $this->validateMessage;
        }
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        try {
            $ret = $v->failException(true)->check($data);
        } catch (\Exception $e) {
            return $this->return(['code' => 0, 'msg' => $e->getMessage(), 'data' => []]);
        }
        return $ret ;
    }

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

    /**
     * 快速修改字段值
     * 其它子控制器可以继承此方法，并做一定要做一些安全限制，必须只允许修改哪些字段防止安全问题。
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    protected function editField()
    {
        $table = input('post.table');
        $field = input('post.field');
        $newval = input('post.newval');
        if (is_array($newval)) {
            $newval = implode(',', $newval);
        }
        $keyVal = input('post.id');
        $where = [];
        $tableArray = explode('_', $table);
        $moduleName = $tableArray[0];
        // 兼容表名就是模块名
        if (count($tableArray) > 1) {
            unset($tableArray[0]);
        }
        foreach ($tableArray as $key => &$value) {
            ucfirst($value);
        }
        $tableModel = implode('', $tableArray);
        $class = '\\uiadmin\\' . $moduleName . '\\model\\' . ucfirst($tableModel);
        $model = new $class();
        $ret = $model->where($where)
            ->where('id', '=' , $keyVal)
            ->update([$field => $newval]);
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '修改成功', 'data' => []]);
        }
        return $this->return(['code' => 0, 'msg' => '修改失败', 'data' => []]);
    }
}
