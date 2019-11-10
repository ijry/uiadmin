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
 * 菜单管理
 *
 * @author jry <ijry@qq.com>
 */
class Menu extends Admin
{
    private $core_menu;
    private $core_module;

    protected function initialize()
    {
        parent::initialize();
        $this->core_menu = new \app\core\model\Menu();
        $this->core_module = new \app\core\model\Module();
    }

    /**
     * 后台左侧导航列表
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function trees()
    {
        // 获取列表
        $data_list = $this->core_menu
            ->where('delete_time', 0)
            ->where('menu_layer', '=', 'admin')
            ->order('sortnum asc')
            ->select()->toArray();
        foreach ($data_list as $key => &$val) {
            if ($val['menu_type'] > 0 && $val['menu_type'] < 4) {
                $val['admin_api'] = '/' . $val['api_prefix'] . '/admin' . $val['path'] . $val['api_suffix'];
            }
        }
        $tree      = new Tree();
        $menu_tree = $tree->list2tree($data_list, 'path', 'pmenu', 'children', 0, false);

        // 构造动态页面数据
        $ibuilder_list = new \app\core\util\ibuilder\IbuilderList();
        $list_data = $ibuilder_list->init()
            ->addTopButton('add', '添加菜单', ['api' => '/v1/admin/core/menu/add'])
            ->addRightButton('doc', '文档', ['api' => '/v1/admin/core/api/doc', 'width' => '1000', 'title' => 'API文档编辑', 'api_suffix' =>['id']])
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/core/menu/edit', 'title' => '修改菜单'])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/core/menu/delete',
                'title' => '确认要删除该菜单吗？',
                'modal_type' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p>删除菜单不可恢复</p>',
            ])
            ->addColumn('id' , 'ID', ['width' => '50px'])
            ->addColumn('module', '所属模块', ['width' => '80px'])
            ->addColumn('title', '菜单标题', ['width' => '230px'])
            ->addColumn('menu_type', '类型', ['width' => '50px'])
            ->addColumn('api_method', '请求方法', ['width' => '90px'])
            ->addColumn('admin_api', '后台接口', ['minWidth' => '250px'])
            ->addColumn('is_hide', '隐藏', ['width' => '50px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('right_button_list', '操作', [
                'type' => 'template',
                'template' => 'right_button_list'
            ])
            ->setDataList($menu_tree)
            ->getData();

        // 返回数据
        return $this->return(['code' => 200, 'msg' => '成功', 'data' => [
            'list_data' => $list_data
        ]]);
    }

    /**
     * 添加
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function add()
    {
        if(request()->isPost()){
            // 数据验证
            $validate = Validate::make([
                'module'  => 'require',
                'title' => 'require',
                'menu_type' => 'require',
                'path' => 'require',
                'api_prefix' => 'require',
            ],
            [
                'module.require' => '请选择模块',
                'title.require' => '菜单名称必须',
                'menu_type.require' => '菜单类型必须',
                'path.require' => '接口路径必须',
                'api_prefix.require' => '接口前缀必须',
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
            $data_db['api_method'] = implode('|', $data_db['api_method']);

            // 存储数据
            $ret = $this->core_menu->save($data_db);
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '添加成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '添加失败' . $this->core_menu->getError(), 'data' => []]);
            }
        } else {
            // 获取模块列表
            $module_list = $this->core_module
                ->where('status', 1)
                ->order('sortnum asc')
                ->select()->toArray();
            $module_list_select = [];
            foreach ($module_list as $key => $val) {
                $module_list_select[$key]['title'] = $val['title'];
                $module_list_select[$key]['value'] = $val['name'];
            }

            // 获取菜单基于标题的树状列表
            $menu_list = $this->core_menu
                ->where('menu_type', '<', 4)
                ->order('sortnum asc')
                ->select()->toArray();
            $tree      = new Tree();
            $menu_tree = $tree->array2tree($menu_list, 'title', 'path', 'pmenu', 0, false);
            $menu_tree_select = [];
            foreach ($menu_tree as $key1 => $val1) {
                $menu_tree_select[$key1]['title'] = $val1['title_show'];
                $menu_tree_select[$key1]['value'] = $val1['path'];
            }

            // 构造动态页面数据
            $ibuilder_form = new \app\core\util\ibuilder\IbuilderForm();
            $form_data = $ibuilder_form->init()
                ->setFormMethod('post')
                ->addFormItem('module', '模块', 'select', '', [
                    'placeholder' => '请选择模块',
                    'tip' => '模块是一个可分享使用的最小功能包',
                    'options' => $module_list_select
                ])
                ->addFormItem('pmenu', '上级菜单', 'select', '', [
                    'placeholder' => '请选择上级菜单',
                    'tip' => '请选择上级菜单',
                    'options' => $menu_tree_select
                ])
                ->addFormItem('icon', '菜单图标', 'text', '', [
                    'placeholder' => '请输入菜单图标',
                    'tip' => '菜单图标是显示在后台左侧菜单中的'
                ])
                ->addFormItem('title', '菜单标题', 'text', '', [
                    'placeholder' => '请输入菜单标题',
                    'tip' => '菜单标题是显示在左侧列表中的'
                ])
                ->addFormItem('tip', '菜单说明', 'text', '', [
                    'placeholder' => '请输入菜单说明',
                    'tip' => '好的说明有助于用户理解'
                ])
                ->addFormItem('menu_type', '菜单类型', 'radio', '', [
                    'placeholder' => '请选择菜单类型',
                    'tip' => '请选择菜单类型',
                    'options' => [
                        ['title' => '分组', 'value' => 0],
                        ['title' => '左侧导航', 'value' => 1],
                        ['title' => '页面按钮', 'value' => 2],
                        ['title' => '纯接口', 'value' => 3]
                    ]
                ])
                ->addFormItem('path', '接口路径', 'text', '', [
                    'placeholder' => '请输入接口路径',
                    'tip' => '接口路径举例：/core/user/lists'
                ])
                ->addFormItem('api_prefix', '接口前缀', 'text', 'v1', [
                    'placeholder' => '接口前缀',
                    'tip' => '一般默认v1'
                ])
                ->addFormItem('api_suffix', '接口后缀', 'text', '', [
                    'placeholder' => '请输入接口后缀参数',
                    'tip' => '接口参数举例：/:id/:name'
                ])
                ->addFormItem('api_params', '接口参数', 'text', '', [
                    'placeholder' => '请输入接口参数实际值',
                    'tip' => '接口参数的实际值举例：/core'
                ])
                ->addFormItem('api_method', '请求方法', 'checkbox', [], [
                    'placeholder' => '请勾选请求方法',
                    'tip' => '尽量符合Restful风格',
                    'options' => [
                        ['title' => 'GET', 'value' => 'GET',],
                        ['title' => 'POST', 'value' => 'POST',],
                        ['title' => 'PUT', 'value' => 'PUT',],
                        ['title' => 'DELETE', 'value' => 'DELETE',]
                    ]
                ])
                ->addFormItem('route_type', '动态页面', 'radio', 1, [
                    'placeholder' => '请选择是否自动生成页面',
                    'tip' => '系统内容了动态页面技术，可以自动生成后台前端页面',
                    'options' => [
                        ['title' => 'Vue路由', 'value' => 'route',],
                        ['title' => 'iBuilder动态列表', 'value' => 'list',],
                        ['title' => 'iBuilder动态表单', 'value' => 'form',]
                    ]
                ])
                ->addFormItem('is_hide', '是否隐藏', 'radio', 0, [
                    'placeholder' => '请选择是否隐藏',
                    'tip' => '有时候一些功能不需要可以隐藏',
                    'options' => [
                        ['title' => '是', 'value' => 1,],
                        ['title' => '否', 'value' => 0,]
                    ]
                ])
                ->addFormItem('sortnum', '排序', 'text', '', [
                    'placeholder' => '请输入排序',
                    'tip' => '请输入排序'
                ])
                ->addFormRule('module', [
                    ['required' => true, 'message' => '请选择所属模块', 'trigger' => 'change'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写菜单标题', 'trigger' => 'blur'],
                ])
                ->addFormRule('menu_type', [
                    ['required' => true, 'type' => 'number', 'message' => '请选择菜单类型', 'trigger' => 'change'],
                ])
                ->addFormRule('path', [
                    ['required' => true, 'message' => '请输入接口路径', 'trigger' => 'blur'],
                ])
                ->addFormRule('route_type', [
                    ['required' => true, 'type' => 'string', 'message' => '请选择是页面路由方式', 'trigger' => 'change'],
                ])
                ->addFormRule('is_hide', [
                    ['required' => true, 'type' => 'number', 'message' => '请选择是否隐藏', 'trigger' => 'change'],
                ])
                ->setFormValues()
                ->getData();

            // 返回数据
            return $this->return(
                [
                    'code' => 200,
                    'msg' => '成功',
                    'data' => [
                        'form_data' => $form_data
                    ]
                ]
            );
        }
    }

    /**
     * 修改
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function edit($id)
    {
        if(request()->isPut()){
            // 数据验证
            $validate = Validate::make([
                'module'  => 'require',
                'title' => 'require',
                'menu_type' => 'require',
                'path' => 'require',
                'api_prefix' => 'require',
            ],
            [
                'module.require' => '请选择模块',
                'title.require' => '菜单名称必须',
                'menu_type.require' => '菜单类型必须',
                'path.require' => '接口路径必须',
                'api_prefix.require' => '接口前缀必须',
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
            if (isset($data_db['api_method'])) {
                $data_db['api_method'] = implode('|', $data_db['api_method']);
            }

            // 存储数据
            try {
                $ret = $this->core_menu->save($data_db, ['id' => $id]);
            } catch (\Exception $e) {
                return $this->return(['code' => 0, 'msg' => '修改失败' . json_encode($e), 'data' => []]);
            }
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '修改成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '修改失败' . $this->core_menu->getError(), 'data' => []]);
            }
        } else {
            // 获取菜单信息
            // 用户信息
            $info = $this->core_menu
                ->where('id', $id)
                ->find();
            $info['api_method'] = explode('|', $info['api_method']);

            // 获取模块列表
            $module_list = $this->core_module
                ->where('status', 1)
                ->order('sortnum asc')
                ->select()->toArray();
            $module_list_select = [];
            foreach ($module_list as $key => $val) {
                $module_list_select[$key]['title'] = $val['title'];
                $module_list_select[$key]['value'] = $val['name'];
            }

            // 获取菜单基于标题的树状列表
            $menu_list = $this->core_menu
                ->where('menu_type', '<', 4)
                ->order('sortnum asc')
                ->select()->toArray();
            $tree      = new Tree();
            $menu_tree = $tree->array2tree($menu_list, 'title', 'path', 'pmenu', 0, false);
            $menu_tree_select = [];
            foreach ($menu_tree as $key => $val) {
                $menu_tree_select[$key]['title'] = $val['title_show'];
                $menu_tree_select[$key]['value'] = $val['path'];
            }

            // 构造动态页面数据
            $ibuilder_form = new \app\core\util\ibuilder\IbuilderForm();
            $form_data = $ibuilder_form->init()
                ->setFormMethod('put')
                ->addFormItem('module', '模块', 'select', '', [
                    'placeholder' => '请选择模块',
                    'tip' => '模块是一个可分享使用的最小功能包',
                    'options' => $module_list_select
                ])
                ->addFormItem('pmenu', '上级菜单', 'select', '', [
                    'placeholder' => '请选择上级菜单',
                    'tip' => '请选择上级菜单',
                    'options' => $menu_tree_select
                ])
                ->addFormItem('icon', '菜单图标', 'text', '', [
                    'placeholder' => '请输入菜单图标',
                    'tip' => '菜单图标是显示在后台左侧菜单中的'
                ])
                ->addFormItem('title', '菜单标题', 'text', '', [
                    'placeholder' => '请输入菜单标题',
                    'tip' => '菜单标题是显示在左侧列表中的'
                ])
                ->addFormItem('tip', '菜单说明', 'text', '', [
                    'placeholder' => '请输入菜单说明',
                    'tip' => '好的说明有助于用户理解'
                ])
                ->addFormItem('menu_type', '菜单类型', 'radio', '', [
                    'placeholder' => '请选择菜单类型',
                    'tip' => '请选择菜单类型',
                    'options' => [
                        ['title' => '分组', 'value' => 0],
                        ['title' => '左侧导航', 'value' => 1],
                        ['title' => '页面按钮', 'value' => 2],
                        ['title' => '纯接口', 'value' => 3]
                    ]
                ])
                ->addFormItem('path', '接口路径', 'text', '', [
                    'placeholder' => '请输入接口路径',
                    'tip' => '接口路径举例：/core/user/lists'
                ])
                ->addFormItem('api_prefix', '接口前缀', 'text', 'v1', [
                    'placeholder' => '接口前缀',
                    'tip' => '一般默认v1'
                ])
                ->addFormItem('api_suffix', '接口后缀', 'text', '', [
                    'placeholder' => '请输入接口后缀参数',
                    'tip' => '接口参数举例：/:id/:name'
                ])
                ->addFormItem('api_params', '接口参数', 'text', '', [
                    'placeholder' => '请输入接口参数实际值',
                    'tip' => '接口参数的实际值举例：/core'
                ])
                ->addFormItem('api_method', '请求方法', 'checkbox', '', [
                    'placeholder' => '请勾选请求方法',
                    'tip' => '尽量符合Restful风格',
                    'options' => [
                        ['title' => 'GET', 'value' => 'GET',],
                        ['title' => 'POST', 'value' => 'POST',],
                        ['title' => 'PUT', 'value' => 'PUT',],
                        ['title' => 'DELETE', 'value' => 'DELETE',]
                    ]
                ])
                ->addFormItem('route_type', '动态页面', 'radio', 1, [
                    'placeholder' => '请选择是否自动生成页面',
                    'tip' => '系统内容了动态页面技术，可以自动生成后台前端页面',
                    'options' => [
                        ['title' => 'Vue路由', 'value' => 'route',],
                        ['title' => 'iBuilder动态列表', 'value' => 'list',],
                        ['title' => 'iBuilder动态表单', 'value' => 'form',]
                    ]
                ])
                ->addFormItem('is_hide', '是否隐藏', 'radio', 0, [
                    'placeholder' => '请选择是否隐藏',
                    'tip' => '有时候一些功能不需要可以隐藏',
                    'options' => [
                        ['title' => '是', 'value' => 1,],
                        ['title' => '否', 'value' => 0,]
                    ]
                ])
                ->addFormItem('sortnum', '排序', 'text', '', [
                    'placeholder' => '请输入排序',
                    'tip' => '请输入排序'
                ])
                ->addFormRule('module', [
                    ['required' => true, 'message' => '请选择所属模块', 'trigger' => 'change'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写菜单标题', 'trigger' => 'blur'],
                ])
                ->addFormRule('menu_type', [
                    ['required' => true, 'type' => 'number', 'message' => '请选择菜单类型', 'trigger' => 'change'],
                ])
                ->addFormRule('path', [
                    ['required' => true, 'message' => '请输入接口路径', 'trigger' => 'blur'],
                ])
                ->addFormRule('route_type', [
                    ['required' => true, 'type' => 'string', 'message' => '请选择是页面路由方式', 'trigger' => 'change'],
                ])
                ->addFormRule('is_hide', [
                    ['required' => true, 'type' => 'number', 'message' => '请选择是否隐藏', 'trigger' => 'change'],
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

    /**
     * 后台左侧导航列表路由规则
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function lists()
    {
        // 计算路由
        $data_list = $this->core_menu
            ->where('menu_type', 'in', '1,2,3')
            ->select();
        foreach ($data_list as $key => &$val) {
            $val['api'] = $val['api_prefix'] . '/admin' . $val['path'];
        }
        return $this->return(['code' => 200, 'msg' => '成功', 'data' => ['data_list' => $data_list]]);
    }

    /**
     * 删除
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function delete($id)
    {
        // 子菜单检测
        $info = $this->core_menu
            ->where(['id' => $id])
            ->find();
        $exist = $this->core_menu
            ->where(['pmenu' => $info['path']])
            ->count();
        if ($exist > 0) {
            return $this->return(['code' => 0, 'msg' => '存在子菜单无法删除', 'data' => []]);
        }

        $ret = $this->core_menu
            ->where(['id' => $id])
            ->find()
            ->delete();
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return $this->return(['code' => 0, 'msg' => '删除错误' . $this->core_menu->getError(), 'data' => []]);
        }
    }
}
