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
        // initialize
        parent::initialize();

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
        // 判断pathinfo中是否以api/开头来判断时API请求还是页面请求
        if (\think\helper\Str::startsWith(request()->pathinfo(), 'api/')) {
            if (\think\helper\Str::contains(request()->pathinfo(), '.html')) {
                dump($data);
            } else {
                return json($data);
            }
        } else {
            if (isset($data['data'])) {
                if ($data['code'] == 401) {
                    return $this->redirect('core/admin.user/login');
                } else {
                    if (is_file(env('root_path') . '.env')) {
                        $data_list = Db::name('core_config')
                            ->field('config_cate,config_type,name,value')
                            ->where('module', 'core')
                            ->select();
                        $return = [];
                        foreach ($data_list as $key => &$value) {
                            if ($value['config_type'] == 'array') {
                                $return[$value['name']] = parse_attr($value['value']);
                            } else if (in_array($value['config_type'], ['images', 'files'])) {
                                if ($value['value'] == '') {
                                    $value['value'] = [];
                                } else {
                                    $value['value'] = json_decode($value['value'], true);
                                }
                                $return[$value['name']] = $value['value'];
                            } else {
                                $return[$value['name']] = $value['value'];
                            }
                        }
                        // 首页地址
                        $return['homepage'] = request()->rootUrl();
                        $data['data']['config_core'] = $data['data']['site_info'] = $return;
                    }
                    if (!isset($data['data']['ibuilder_base'])) {
                        $data['data']['ibuilder_base'] = 'core@public/base';
                    }
                    $template = '';
                    if (isset($data['data']['template'])) {
                        $template = $data['data']['template'];
                    }
                    $this->assign($data['data']);
                    if (isset($data['data']['list_data'])) {
                        return $this->fetch('core@admin/ibuilder/list');
                    } else if(isset($data['data']['form_data'])) {
                        return $this->fetch('core@admin/ibuilder/form');
                    } else {
                        if (is_file(env('root_path') . '.env')
                            && !\think\helper\Str::startsWith(request()->path(), 'admin/')
                            && request()->path() != 'core/install/step5') {
                            // 模板全局根目录
                            $view_base = env('root_path') . 'public/view/' . $data['data']['config_core']['theme'] . '/';
                            $this->view->config('view_base', $view_base);

                            // 设置模板字符替换变量
                            $tpl_replace_string = config('template.tpl_replace_string');
                            $tpl_replace_string_add = [];
                            $tpl_replace_string_add['__CSS__'] = request()->rootUrl() . '/view/'
                                . $data['data']['config_core']['theme'] . '/core/public/css';
                            $tpl_replace_string_add['__JS__'] = request()->rootUrl() . '/view/'
                                . $data['data']['config_core']['theme'] . '/core/public/js';
                            $tpl_replace_string_add['__IMG__'] = request()->rootUrl() . '/view/'
                                . $data['data']['config_core']['theme'] . '/core/public/img';
                            $tpl_replace_string_add['__LIBS__'] = request()->rootUrl() . '/view/'
                                . $data['data']['config_core']['theme'] . '/core/public/libs';
                            $tpl_replace_string_add['__FONTS__'] = request()->rootUrl() . '/view/'
                                . $data['data']['config_core']['theme'] . '/core/public/fonts';
                            $tpl_replace_string = array_merge($tpl_replace_string, $tpl_replace_string_add);
                            $this->view->config('tpl_replace_string', $tpl_replace_string);
                        }
                        return $this->fetch($template);
                    }
                }
            } else {
                return $this->fetch();
            }
        }
    }

    /**
     * 判断用户是否登陆方法
     * @param  string $token
     * @return array
     * @author jry <ijry@qq.com>
     */
    protected function isLogin($redirect = 0)
    {
        // 获取token
        $token = Request::header('Authorization');
        if (!$token) {
            $token = session('Authorization'); // 支持session
            if (!$token) {
                return ['code' => 401, 'msg' => 'AuthorizationToken未提交', 'data' => ['redirect' => $redirect]];
            }
        }
        $token_array = explode(' ', $token);
        if (!isset($token_array[1])) {
            return ['code' => 401, 'msg' => 'token格式错误', 'data' => ['redirect' => $redirect]];
        }
        $jwt = $token_array[1]; //签发的Token
        if (!$jwt) {
            return ['code' => 401, 'msg' => '未提交用户Token', 'data' => ['redirect' => $redirect]];
        }

        // jwt验证
        try {
            // 数据库验证
            $info = Db::name('core_login')
                ->removeOption('where')
                ->where('token', $jwt)
                ->find();
            if (!$info) {
                return ['code' => 401, 'msg' => 'token不存在', 'data' => ['redirect' => $redirect]];
            }
            if ($info['expire_time'] <= time()) {
                return ['code' => 401, 'msg' => '登录过期请重新登录', 'data' => ['redirect' => $redirect]];
            }

            //解密
            JWT::$leeway = 60; // 当前时间减去60，把时间留点余地
            $decoded = JWT::decode($jwt, $info['key'], ['HS256']); // HS256方式，这里要和签发的时候对应
            $arr = (array)$decoded;
            if ($arr['data']->uid != $info['uid']) {
                return ['code' => 401, 'msg' => '数据异常请联系管理员', 'data' => ['redirect' => $redirect]];
            }
            $arr['data']->token = $jwt;
            return ['code' => 200, 'data' => $arr];
        } catch(\Firebase\JWT\SignatureInvalidException $e) {  // 签名不正确
            return ['code' => 401, 'msg' => $e->getMessage(), 'data' => ['redirect' => $redirect]];
        }catch(\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            return ['code' => 401, 'msg' => $e->getMessage(), 'data' => ['redirect' => $redirect]];
        }catch(\Firebase\JWT\ExpiredException $e) {  // token过期
            return ['code' => 401, 'msg' => $e->getMessage(), 'data' => ['redirect' => $redirect]];
        }catch(Exception $e) {  //其他错误
            return ['code' => 401, 'msg' => $e->getMessage(), 'data' => ['redirect' => $redirect]];
        }
    }
}
