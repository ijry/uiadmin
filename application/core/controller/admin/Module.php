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

namespace app\core\controller\admin;

use think\Db;
use think\Validate;
use think\facade\Request;
use app\core\controller\common\Admin;
use app\core\util\Tree;

/**
 * 模块管理
 *
 * @author jry <ijry@qq.com>
 */
class Module extends Admin
{
    private $core_module;
    private $core_menu;
    private $core_config;

    protected function initialize()
    {
        parent::initialize();
        $this->core_module = new \app\core\model\Module();
        $this->core_menu = new \app\core\model\Menu();
        $this->core_config = new \app\core\model\Config();
    }

    /**
     * 安装模块
     *
     * @return \think\Response
     */
    public function import($name)
    {
        // 启动事务
        Db::startTrans();
        try {
            if (is_dir(env('app_path') . $name)) {
                $module_insall = file_get_contents(env('app_path') . $name . '/install/install.json');
                $module_insall = json_decode($module_insall, true);
                $module_insall['info']['status'] = 1;

                // 导入基础信息
                $this->core_module->save($module_insall['info']);

                // 导入配置
                $this->core_config->saveAll($module_insall['config']);
                // 导入菜单及API
                $this->core_menu->saveAll($module_insall['api']);
                // 导入数据表
                foreach ($module_insall['tables'] as $key => $value) {
                    if (isset($value['table_create'])) {
                        $value['table_create'] = implode("", $value['table_create']);
                        if (false === Db::execute($value['table_create'])) {
                            throw new \Exception($value['table_name'] . "创建出错", 0);
                        }
                    }
                    if (isset($value['table_rows'])) {
                        if (false === Db::table($value['table_name'])->insertAll($value['table_rows'])) {
                            throw new \Exception($value['table_name'] . "添加记录出错", 0);
                        }
                    }
                }
            } else {
                throw new \Exception("不存在", 0);
            }
            Db::commit(); // 提交事务
            return $this->return([
                'code' => 200, 'msg' => '恭喜您，安装成功！', 'data' => []
            ]);
        } catch (Exception $e) {
            Db::rollback(); // 回滚事务
            return $this->return([
                'code' => 0, 'msg' => $e->getMessage(), 'data' => []
            ]);
        }
    }

    /**
     * 模块列表
     *
     * @return \think\Response
     */
    public function lists()
    {
        // 模块列表
        $data_list = $this->core_module->select()->toArray();

        // 获取本地未安装模块
        // 获取已经安装的模块名称
        $installed_names = $this->core_module->column('name');
        $dir_list = \app\core\util\File::get_dirs(env('app_path'))['dir'];
        foreach ($dir_list as $key => $value) {
            if ($value == '.' || $value == '..') {
                continue;
            }
            if (!in_array($value, $installed_names)) {
                $module = [];
                $module['id'] = '';
                $module['name'] = $value;
                $module_insall = file_get_contents(env('app_path') . $value . '/install/install.json');
                $module_insall = json_decode($module_insall, true);
                $module['title'] = $module_insall['info']['title'];
                $module['description'] = $module_insall['info']['description'];
                $module['developer'] = $module_insall['info']['developer'];
                $module['website'] = $module_insall['info']['website'];
                $module['version'] = $module_insall['info']['version'];
                $module['build'] = $module_insall['info']['build'];
                $data_list[] = $module;
            }
        }

        foreach ($data_list as $key => &$value1) {
            // 兼容接口文档Tree组件异步加载需要
            $value1['loading'] = false;
            $value1['children'] = [];
            // 右侧按钮
            $value1['right_button_list'] = [];
            if (!$value1['id']) {
                $value1['right_button_list'][] = [
                    'name' => 'info',
                    'title' => '安装',
                    'page_data' => [
                        'form_method' => 'post',
                        'api' => '/v1/admin/core/module/import',
                        'title' => '确认要安装该模块吗？',
                        'api_suffix' => ['name'],
                        'modal_type' => 'confirm',
                        'width' => '600',
                        'okText' => '立即安装',
                        'cancelText' => '取消安装',
                        'content' => '<p>安装模块后您将可以使用该模块的功能</p>',
                    ],
                    'style' => ['size' => 'small', 'type' => 'success']
                ];
            } else {
                $value1['right_button_list'][] = [
                    'name' => 'config',
                    'title' => '设置',
                    'page_data' => [
                        'modal_type' => 'form',
                        'api' => '/v1/admin/core/config/saveBatch/',
                        'width' => '1000',
                        'api_suffix' => ['name'],
                        'title' => '配置'
                    ],
                    'style' => ['size' => 'small', 'type' => 'primary']
                ];
                $value1['right_button_list'][] = [
                    'name' => 'edit',
                    'title' => '修改',
                    'page_data' => [
                        'modal_type' => 'form',
                        'api' => '/v1/admin/core/module/edit',
                        'width' => '1000',
                        'title' => '修改模块信息'
                    ],
                    'style' => ['size' => 'small']
                ];
            }
        }

        // 转换成树
        $tree      = new Tree();
        $data_list = $tree->list2tree($data_list);

        // 构造动态页面数据
        $ibuilder_list = new \app\core\util\ibuilder\IbuilderList();
        $list_data = $ibuilder_list->init()
            ->addTopButton('add', '创建新模块', ['api' => '/v1/admin/core/module/add'])
            ->addColumn('id' , 'ID', ['width' => '50px'])
            ->addColumn('name', '名称', ['width' => '120px'])
            ->addColumn('title', '标题', ['width' => '120px'])
            ->addColumn('description', '描述', ['width' => '200px'])
            ->addColumn('developer', '开发者', ['width' => '80px'])
            ->addColumn('version', '版本', ['width' => '80px'])
            ->addColumn('build', 'Build', ['width' => '150px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('status', '状态', ['width' => '50px'])
            ->addColumn('right_button_list', '操作', [
                'minWidth' => '100px',
                'type' => 'template',
                'template' => 'right_button_list'
            ])
            ->setDataList($data_list)
            ->getData();

        // 返回数据
        return $this->return([
            'code' => 200, 'msg' => '成功', 'data' => [
                'list_data' => $list_data
            ]
        ]);
    }

    /**
     * 添加
     *
     * @return \think\Response
     */
    public function add()
    {
        if (request()->isPost()) {
            // 数据验证
            $validate = Validate::make([
                'name'  => 'require',
                'title' => 'require',
                'description' => 'require',
                'developer' => 'require',
                'website' => 'require',
                'version' => 'require',
                'build' => 'require'
            ],
            [
                'name.require' => '模块名称必须',
                'title.require' => '模块标题必须',
                'description.require' => '模块简介必须',
                'developer.require' => '开发者必须',
                'website.require' => '开发者网站必须',
                'version.require' => '版本号必须',
                'build.require' => 'build版本号必须',
            ]);
            $data = input('post.');
            if (!$validate->check($data)) {
                return $this->return(['code' => 0, 'msg' => $validate->getError(), 'data' => []]);
            }

            // 数据构造
            $data_db = $data;
            if (count($data_db) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            $data_db['status']   = 1;


            // 是否存在模块名称
            $exist = $this->core_module
                ->where('name', $data_db['name'])
                ->count();
            if ($exist > 0) {
                return $this->return(['code' => 0, 'msg' => '模块名称已经存在请换一个', 'data' => []]);
            }


            // 启动事务
            Db::startTrans();
            try {
                //创建目录
                $module_name = $data_db['name'];
                $path = [
                    '__file__'   => [
                        $module_name . '/common.php',
                        $module_name . '/install/install.json'
                    ],
                    '__dir__'    => [
                        $module_name . '/behavior',
                        $module_name . '/controller',
                        $module_name . '/controller/admin',
                        $module_name . '/model',
                        $module_name . '/install',
                        $module_name . '/job',
                        $module_name . '/lang',
                        $module_name . '/service',
                        $module_name . '/util',
                        $module_name . '/validate',
                        $module_name . '/view'
                    ]
                ];
                \think\facade\Build::run($path);

                // 存储数据
                $ret = $this->core_module->save($data_db);
                if (!$ret) {
                    return $this->return(['code' => 0, 'msg' => '添加模块失败:' . $this->core_module->getError(), 'data' => []]);
                }

                // 创建模块后台API分组
                if ($data_db['create_menu_group']) {
                    $data_api = [];
                    $data_api['module'] = $data_db['name'];
                    $data_api['menu_type'] = 0;
                    $ret_api = true;
                    if (!$this->core_menu->where($data_api)->find()) {
                        $data_api['title'] = $data_db['title'];
                        $data_api['path'] = '/' . $module_name;
                        $ret_api = $this->core_menu->save($data_api);
                    }
                } else {
                    $ret_api = true;
                }
                if ($ret_api) {
                    // 提交事务
                    Db::commit();
                    return $this->return(['code' => 200, 'msg' => '添加模块成功', 'data' => []]);
                } else {
                    return $this->return(['code' => 0, 'msg' => '添加模块失败:' . $this->core_menu->getError(), 'data' => []]);
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
        } else {
            // 构造动态页面数据
            $ibuilder_form = new \app\core\util\ibuilder\IbuilderForm();
            $form_data = $ibuilder_form->init()
                ->setFormMethod('post')
                ->addFormItem('name', '模块名称', 'text', '', [
                    'placeholder' => '请输入模块名称',
                    'tip' => '模块名称由小写英文字母加下划线组成'
                ])
                ->addFormItem('title', '模块标题', 'text', '', [
                    'placeholder' => '请输入模块标题',
                    'tip' => '模块标题一般为了方便用户理解，比如商城'
                ])
                ->addFormItem('description', '简介', 'text', '', [
                    'placeholder' => '请输入模块简介',
                    'tip' => '稍微详细的模块功能介绍'
                ])
                ->addFormItem('developer', '开发者', 'text', '', [
                    'placeholder' => '请输入开发者名称',
                ])
                ->addFormItem('website', '开发者网站', 'text', '', [
                    'placeholder' => '请输入开发者开发者网站',
                ])
                ->addFormItem('version', '版本号', 'text', '', [
                    'placeholder' => '请输入版本号如0.1.0',
                    'tip' => '版本号由三位数字组成x.x.x'
                ])
                ->addFormItem('build', 'build版本号', 'text', '', [
                    'placeholder' => '请输入build版本号如beta1_20190301',
                    'tip' => 'build版本号是一个更细小的版本单位，如release_20190301'
                ])
                ->addFormItem('create_menu_group', '菜单分组', 'radio', 0, [
                    'options' => ['1' => '是', '0' => '否'],
                    'tip' => '如果您的模块具有后台左侧菜单请选择是，如果是作为第三方SDK或者没有后台功能请选择否'
                ])
                ->addFormItem('sortnum', '排序', 'text', '')
                ->addFormRule('name', [
                    ['required' => true, 'message' => '请填写昵称', 'trigger' => 'blur'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写用户名', 'trigger' => 'blur'],
                ])
                ->addFormRule('description', [
                    ['required' => true, 'message' => '请填写模块简介', 'trigger' => 'blur'],
                ])
                ->addFormRule('developer', [
                    ['required' => true, 'message' => '请填写开发者名称', 'trigger' => 'blur'],
                ])
                ->addFormRule('website', [
                    ['required' => true, 'message' => '请填写开发者网站', 'trigger' => 'blur'],
                ])
                ->addFormRule('version', [
                    ['required' => true, 'message' => '请填写版本号，如0.1.0', 'trigger' => 'blur'],
                ])
                ->addFormRule('build', [
                    ['required' => true, 'message' => '请输入build版本号如beta1_201902241200', 'trigger' => 'blur'],
                ])
                ->setFormValues()
                ->getData();

            //返回数据
            return $this->return([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'form_data' => $form_data
                ]
            ]);
        }
    }

    /**
     * 修改
     *
     * @return \think\Response
     */
    public function edit($id)
    {
        if (request()->isPut()) {
            // 数据验证
            $validate = Validate::make([
                'name'  => 'require',
                'title' => 'require',
                'description' => 'require',
                'developer' => 'require',
                'website' => 'require',
                'version' => 'require',
                'build' => 'require'
            ],
            [
                'name.require' => '模块名称必须',
                'title.require' => '模块标题必须',
                'description.require' => '模块简介必须',
                'developer.require' => '开发者必须',
                'website.require' => '开发者网站必须',
                'version.require' => '版本号必须',
                'build.require' => 'build版本号必须',
            ]);
            $data = input('post.');
            if (!$validate->check($data)) {
                return $this->return(['code' => 0, 'msg' => $validate->getError(), 'data' => []]);
            }

            //数据构造
            // 核心模块特殊处理
            if ($id == 1) {
                $data_db = [
                    'version' => $data['version'],
                    'build' => $data['build'],
                ];
            } else {
                $data_db = $data;
            }
            if (count($data_db) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }

            // 存储数据
            $ret = $this->core_module->update($data_db, ['id' => $id]);
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '修改模块信息成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '修改模块信息失败:' . $this->core_module->getError(), 'data' => []]);
            }
        } else {
            // 模块信息
            $info = $this->core_module
                ->where('id', $id)
                ->find();

            // 构造动态页面数据
            $ibuilder_form = new \app\core\util\ibuilder\IbuilderForm();
            $form_data = $ibuilder_form->init()
                ->setFormMethod('put')
                ->addFormItem('name', '模块名称', 'text', '', [
                    'placeholder' => '请输入模块名称',
                    'tip' => '模块名称由小写英文字母加下划线组成'
                ])
                ->addFormItem('title', '模块标题', 'text', '', [
                    'placeholder' => '请输入模块标题',
                    'tip' => '模块标题一般为了方便用户理解，比如商城'
                ])
                ->addFormItem('description', '简介', 'text', '', [
                    'placeholder' => '请输入模块简介',
                    'tip' => '稍微详细的模块功能介绍'
                ])
                ->addFormItem('developer', '开发者', 'text', '', [
                    'placeholder' => '请输入开发者名称',
                ])
                ->addFormItem('website', '开发者网站', 'text', '', [
                    'placeholder' => '请输入开发者开发者网站',
                ])
                ->addFormItem('version', '版本号', 'text', '', [
                    'placeholder' => '请输入版本号如0.1.0',
                    'tip' => '版本号由三位数字组成x.x.x'
                ])
                ->addFormItem('build', 'build版本号', 'text', '', [
                    'placeholder' => '请输入build版本号如beta1_20190301',
                    'tip' => 'build版本号是一个更细小的版本单位，如release_20190301'
                ])
                ->addFormItem('sortnum', '排序', 'text', '')
                ->addFormRule('name', [
                    ['required' => true, 'message' => '请填写昵称', 'trigger' => 'blur'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写用户名', 'trigger' => 'blur'],
                ])
                ->addFormRule('description', [
                    ['required' => true, 'message' => '请填写模块简介', 'trigger' => 'blur'],
                ])
                ->addFormRule('developer', [
                    ['required' => true, 'message' => '请填写开发者名称', 'trigger' => 'blur'],
                ])
                ->addFormRule('website', [
                    ['required' => true, 'message' => '请填写开发者网站', 'trigger' => 'blur'],
                ])
                ->addFormRule('version', [
                    ['required' => true, 'message' => '请填写版本号，如0.1.0', 'trigger' => 'blur'],
                ])
                ->addFormRule('build', [
                    ['required' => true, 'message' => '请输入build版本号如beta1_201902241200', 'trigger' => 'blur'],
                ])
                ->setFormValues($info)
                ->getData();

            // 返回数据
            return $this->return([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'form_data' => $form_data
                ]
            ]);
        }
    }
}
