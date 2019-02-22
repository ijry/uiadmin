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
 * 配置
 */
class Config extends Admin
{
    private $core_module;

    protected function initialize()
    {
        parent::initialize();
        $this->core_config = Db::name('core_config');
    }

    /**
     * 模块列表
     *
     * @return \think\Response
     */
    public function lists()
    {
        //用户列表
        $data_list = $this->core_config
            ->removeOption('where')
            ->select();

        //构造动态页面数据
        $ia_dylist      = new \app\core\util\iadypage\IaDylist();
        $list_data = $ia_dylist->init()
            ->addTopButton('add', '添加', ['api' => '/v1/admin/core/config/add'])
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/core/config/edit', 'title' => '修改配置信息'])
            ->addColumn('id' , 'ID', ['width' => '50px'])
            ->addColumn('name', '名称', ['width' => '100px'])
            ->addColumn('title', '标题', ['width' => '100px'])
            ->addColumn('config_cate', '分组', ['width' => '80px'])
            ->addColumn('config_type', '配置类型', ['width' => '80px'])
            ->addColumn('placeholder', 'placeholder', ['width' => '150px'])
            ->addColumn('tip', '说明', ['width' => '200px'])
            ->addColumn('is_system', '系统', ['width' => '50px'])
            ->addColumn('is_dev', '开发者', ['width' => '80px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('status', '状态', ['width' => '50px'])
            ->addColumn('right_button_list', '操作', [
                'minWidth' => '50px',
                'type' => 'template',
                'template' => 'right_button_list'
            ])
            ->getData();
        
        //返回数据
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
                'config_cate'  => 'require',
                'name' => 'require',
                'title' => 'require',
                'config_type' => 'require'
            ],
            [
                'config_cate.require' => '配置分组必须',
                'name.require' => '配置名称必须',
                'title.require' => '配置标题必须',
                'config_type.require' => '配置类型必须'
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

            // 存储数据
            $ret = $this->core_config->insert($data_db);
            if ($ret) {
                return json(['code' => 200, 'msg' => '添加成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '添加失败', 'data' => []]);
            }
        } else {
            //获取分组信息
            $info = $this->core_config
                ->removeOption('where')
                ->where('name', 'config_cate')
                ->find();

            //构造动态页面数据
            $ia_dyform      = new \app\core\util\iadypage\IaDyform();
            $form_data = $ia_dyform->init()
                ->setFormMethod('post')
                ->addFormItem('config_cate', '配置分组', 'radio', '', '请选择分组', '请选择分组', ['options' => parse_attr($info['value'])])
                ->addFormItem('name', '配置名称', 'text', '', '请输入英文', '配置名称一般类似project_name')
                ->addFormItem('title', '配置标题', 'text', '', '请输入配置标题', '配置标题')
                ->addFormItem('config_type', '配置类型', 'select', '', '请选择表单类型', '表单类型', ['options' => $ia_dyform->form_type])
                ->addFormItem('value', '默认值', 'textarea', '', '请输入配置默认值', '默认值')
                ->addFormItem('placeholder', 'placeholder', 'text', '', '请输入配置placeholder', '请输入配置placeholder')
                ->addFormItem('tip', '说明', 'text', '', '请输入配置说明', '请输入配置说明')
                ->addFormItem('extra', '额外项目', 'textarea', '', '请输入配置额外项目', '请输入配置额外项目')
                ->addFormItem('is_system', '是否系统', 'radio', 1, '请选择是否系统配置', '系统配置一般不允许删除等风险操作', [
                    'options' => ['1' => '是', '0' => '否']
                ])
                ->addFormItem('is_dev', '是否开发者', 'radio', 1, '请选择是否开发者配置', '开发者意味着主要是给开发者编辑的配置', [
                    'options' => ['1' => '是', '0' => '否']
                ])
                ->addFormItem('sortnum', '排序', 'text', '', '请输入排序', '请输入排序')
                ->addFormRule('config_cate', [
                    ['required' => true, 'type' => 'number', 'message' => '请选择分组', 'trigger' => 'change'],
                ])
                ->addFormRule('name', [
                    ['required' => true, 'message' => '请填写配置名称', 'trigger' => 'change'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写配置标题', 'trigger' => 'change'],
                ])
                ->addFormRule('config_type', [
                    ['required' => true, 'type' => 'string', 'message' => '请选择配置类型', 'trigger' => 'change'],
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
            $validate = Validate::make([
                'config_cate'  => 'require',
                'name' => 'require',
                'title' => 'require',
                'config_type' => 'require'
            ],
            [
                'config_cate.require' => '配置分组必须',
                'name.require' => '配置名称必须',
                'title.require' => '配置标题必须',
                'config_type.require' => '配置类型必须'
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

            // 存储数据
            $ret = $this->core_config
                ->where('id', $id)
                ->update($data_db);
            if ($ret) {
                return json(['code' => 200, 'msg' => '修改成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '修改失败', 'data' => []]);
            }
        } else {
            //用户信息
            $info = $this->core_config
                ->removeOption('where')
                ->where('id', $id)
                ->find();
            
            //获取分组信息
            $info = $this->core_config
                ->removeOption('where')
                ->where('name', 'config_cate')
                ->find();

            //构造动态页面数据
            $ia_dyform      = new \app\core\util\iadypage\IaDyform();
            $form_data = $ia_dyform->init()
                ->setFormMethod('put')
                ->addFormItem('config_cate', '配置分组', 'radio', '', '请选择分组', '请选择分组', ['options' => parse_attr($info['value'])])
                ->addFormItem('name', '配置名称', 'text', '', '请输入英文', '配置名称一般类似project_name')
                ->addFormItem('title', '配置标题', 'text', '', '请输入配置标题', '配置标题')
                ->addFormItem('config_type', '配置类型', 'select', '', '请选择表单类型', '表单类型', ['options' => $ia_dyform->form_type])
                ->addFormItem('value', '默认值', 'textarea', '', '请输入配置默认值', '默认值')
                ->addFormItem('placeholder', 'placeholder', 'text', '', '请输入配置placeholder', '请输入配置placeholder')
                ->addFormItem('tip', '说明', 'text', '', '请输入配置说明', '请输入配置说明')
                ->addFormItem('extra', '额外项目', 'textarea', '', '请输入配置额外项目', '请输入配置额外项目')
                ->addFormItem('is_system', '是否系统', 'radio', 1, '请选择是否系统配置', '系统配置一般不允许删除等风险操作', [
                    'options' => ['1' => '是', '0' => '否']
                ])
                ->addFormItem('is_dev', '是否开发者', 'radio', 1, '请选择是否开发者配置', '开发者意味着主要是给开发者编辑的配置', [
                    'options' => ['1' => '是', '0' => '否']
                ])
                ->addFormItem('sortnum', '排序', 'text', '', '请输入排序', '请输入排序')
                ->addFormRule('config_cate', [
                    ['required' => true, 'type' => 'number', 'message' => '请选择分组', 'trigger' => 'change'],
                ])
                ->addFormRule('name', [
                    ['required' => true, 'message' => '请填写配置名称', 'trigger' => 'change'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写配置标题', 'trigger' => 'change'],
                ])
                ->addFormRule('config_type', [
                    ['required' => true, 'type' => 'string', 'message' => '请选择配置类型', 'trigger' => 'change'],
                ])
                ->setFormValues($info)
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
     * 删除
     * 
     * @return \think\Response
     */
    public function delete($id)
    {
        $ret = $this->core_config
            ->where(['id' => $id])
            ->delete();
        if ($ret) {
            return json(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return json(['code' => 200, 'msg' => '删除错误', 'data' => []]);
        }
    }
}
