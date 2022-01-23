<?php
/**
 * +----------------------------------------------------------------------
 * | UiAdmin [ 渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
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
    }

    /**
     * 返回数据或者页面
     * @param  array $data
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    protected function return($data, $json = false)
    {
        // 判断pathinfo中是否以api/开头来判断时API请求还是页面请求
        if ($json || \think\helper\Str::startsWith(request()->pathinfo(), 'api')) {
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
                    $dataList = Db::name('core_config')
                        ->field('configCate,configType,name,value')
                        ->where('module', 'core')
                        ->select();
                    $return = [];
                    foreach ($dataList as $key => &$value) {
                        if ($value['configType'] == 'array') {
                            $return[$value['name']] = parse_attr($value['value']);
                        } else if (in_array($value['configType'], ['images', 'files'])) {
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
                    $data['data']['configCore'] = $data['data']['siteInfo'] = $return;
                }
                if (!isset($data['data']['ibuilderBase'])) {
                    $data['data']['ibuilderBase'] = 'core@public/base';
                }
                $template = '';
                if (isset($data['data']['template'])) {
                    $template = $data['data']['template'];
                }
                $this->assign($data['data']);
                if (isset($data['data']['listData'])) {
                    return $this->fetch('core@admin/ibuilder/list');
                } else if(isset($data['data']['formData'])) {
                    return $this->fetch('core@admin/ibuilder/form');
                } else {
                    if (is_file(env('root_path') . '.env')
                        && !\think\helper\Str::startsWith(request()->path(), 'admin')
                        && request()->path() != 'core/install/step5') {
                        // 模板全局根目录
                        $view_base = env('root_path') . 'public/view/' . $data['data']['configCore']['theme'] . '/';
                        $this->view->config('view_base', $view_base);

                        // 设置模板字符替换变量
                        $tpl_replace_string = config('template.tpl_replace_string');
                        $tpl_replace_string_add = [];
                        $tpl_replace_string_add['__CSS__'] = request()->rootUrl() . '/view/'
                            . $data['data']['configCore']['theme'] . '/core/public/css';
                        $tpl_replace_string_add['__JS__'] = request()->rootUrl() . '/view/'
                            . $data['data']['configCore']['theme'] . '/core/public/js';
                        $tpl_replace_string_add['__IMG__'] = request()->rootUrl() . '/view/'
                            . $data['data']['configCore']['theme'] . '/core/public/img';
                        $tpl_replace_string_add['__LIBS__'] = request()->rootUrl() . '/view/'
                            . $data['data']['configCore']['theme'] . '/core/public/libs';
                        $tpl_replace_string_add['__FONTS__'] = request()->rootUrl() . '/view/'
                            . $data['data']['configCore']['theme'] . '/core/public/fonts';
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
            $ret = $user_service->isLogin($token);
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
        $ret = Db::name($table)
            ->where('id', '=' ,$keyVal)
            ->update([$field => $newval]);
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '修改成功', 'data' => []]);
        }
        return $this->return(['code' => 0, 'msg' => '修改失败', 'data' => []]);
    }
}
