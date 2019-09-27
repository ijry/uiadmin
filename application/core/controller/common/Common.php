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
            if (!isset($data['data'])) {
                $data['data'] = [];
            }
            if ($data['code'] == 401) {
                return $this->redirect('core/admin.user/login');
            } else {
                if ($data['code'] !== 200) {
                    if ($data['code'] == 401) {
                        if (\think\helper\Str::startsWith(request()->pathinfo(), 'admin/')) {
                            return $this->redirect('core/admin.user/login');
                        } else {
                            return $this->redirect('core/user/login');
                        }
                    } else {
                        echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                        exit;
                    }
                }
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
        try {
            // 获取token
            $token = Request::header('Authorization');
            if (!$token) {
                $token = session('Authorization'); // 支持session
            }
            $user_service = new \app\core\service\User();
            $ret = $user_service->is_login($token);
            return ['code' => 200, 'msg' => '成功', 'data' => $ret];
        } catch (Exception $e) {
            return ['code' => $e->getCode(), 'msg' => $e->getMessage(), 'data' => ['redirect' => $redirect]];
        }
    }
}
