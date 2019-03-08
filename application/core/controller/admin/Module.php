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

    protected function initialize()
    {
        parent::initialize();
        $this->core_module = new \app\core\model\Module();
    }

    /**
     * 模块列表
     *
     * @return \think\Response
     */
    public function lists()
    {
        // 用户列表
        $data_list = $this->core_module->select()->toArray();
        $tree      = new Tree();
        $data_list = $tree->list2tree($data_list);

        // 构造动态页面数据
        $ia_dylist      = new \app\core\util\iadypage\IaDylist();
        $list_data = $ia_dylist->init()
            ->addTopButton('add', '创建新模块', ['api' => '/v1/admin/core/module/add'])
            ->addRightButton('config', '设置', [
                'api' => '/v1/admin/core/config/saveBatch/',
                'title' => '配置',
                'api_suffix' => ['name']
            ])
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/core/module/edit', 'title' => '修改模块信息'])
            ->addRightButton('export', '导出', ['api' => '/v1/admin/core/module/export', 'title' => '导出模块'])
            ->addColumn('id' , 'ID', ['width' => '50px'])
            ->addColumn('name', '名称', ['width' => '120px'])
            ->addColumn('title', '标题', ['width' => '120px'])
            ->addColumn('description', '描述', ['width' => '240px'])
            ->addColumn('developer', '开发者', ['width' => '80px'])
            ->addColumn('version', '版本', ['width' => '80px'])
            ->addColumn('build', 'Build', ['width' => '150px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('status', '状态', ['width' => '50px'])
            ->addColumn('right_button_list', '操作', [
                'minWidth' => '50px',
                'type' => 'template',
                'template' => 'right_button_list'
            ])
            ->getData();

        // 返回数据
        return json([
            'code' => 200, 'msg' => '成功', 'data' => [
                'data_list' => $data_list,
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
                return json(['code' => 200, 'msg' => $validate->getError(), 'data' => []]);
            }

            // 数据构造
            $data_db = $data;
            if (count($data_db) <= 0 ) {
                return json(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            $data_db['status']   = 1;


            // 是否存在模块名称
            $exist = $this->core_module
                ->where('name', $data_db['name'])
                ->count();
            if ($exist > 0) {
                return json(['code' => 0, 'msg' => '模块名称已经存在请换一个', 'data' => []]);
            }


            // 启动事务
            Db::startTrans();
            try {
                //创建目录
                $module_name = $data_db['name'];
                $path = [
                    '__file__'   => [
                        $module_name . '/common.php',
                        $module_name . '/install/install.php',
                        $module_name . '/install/install.sql',
                        $module_name . '/install/ininstall.sql'
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
                        $module_name . '/validate'
                    ]
                ];
                \think\facade\Build::run($path);

                //存储数据
                $ret = $this->core_module->save($data_db);
                if ($ret) {
                    // 提交事务
                    Db::commit();
                    return json(['code' => 200, 'msg' => '添加模块成功', 'data' => []]);
                } else {
                    return json(['code' => 0, 'msg' => '添加模块失败:' . $this->core_module->getError(), 'data' => []]);
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
        } else {
            //构造动态页面数据
            $ia_dyform      = new \app\core\util\iadypage\IaDyform();
            $form_data = $ia_dyform->init()
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
                    'placeholder' => '请输入build版本号如beta1_201902241200',
                    'tip' => 'build版本号是一个更细小的版本单位，如release_201902241200'
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
            return json([
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
                return json(['code' => 200, 'msg' => $validate->getError(), 'data' => []]);
            }

            //数据构造
            $data_db = $data;
            if (count($data_db) <= 0 ) {
                return json(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }

            // 存储数据
            $ret = $this->core_module->update($data_db, ['id' => $id]);
            if ($ret) {
                return json(['code' => 200, 'msg' => '修改模块信息成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '修改模块信息失败:' . $this->core_module->getError(), 'data' => []]);
            }
        } else {
            // 模块信息
            $info = $this->core_module
                ->where('id', $id)
                ->find();

            // 构造动态页面数据
            $ia_dyform      = new \app\core\util\iadypage\IaDyform();
            $form_data = $ia_dyform->init()
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
                    'placeholder' => '请输入build版本号如beta1_201902241200',
                    'tip' => 'build版本号是一个更细小的版本单位，如release_201902241200'
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
            return json([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'form_data' => $form_data
                ]
            ]);
        }
    }
}
