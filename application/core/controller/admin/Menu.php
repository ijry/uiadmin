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

class Menu extends Admin
{
    private $core_menu;
    private $core_module;

    protected function initialize()
    {
        parent::initialize();
        $this->core_menu = Db::name('core_menu');
        $this->core_module = Db::name('core_module');
    }

    /**
     * 后台左侧导航列表
     *
     * @return \think\Response
     */
    public function trees()
    {
        // 计算路由
        $data_list = $this->core_menu
            ->where(['delete_time' => 0])
            ->order('sortnum asc')
            ->select();
        foreach ($data_list as $key => &$val) {
            if ($val['menu_type'] > 0) {
                $val['admin_api'] = '/' . $val['api_prefix'] . '/admin' . $val['path'] . $val['api_suffix'];
            }
        }
        $tree      = new Tree();
        $menu_tree = $tree->list2tree($data_list, 'path', 'pmenu', 'children', 0, false);

        //构造动态页面数据
        $ia_dylist      = new \app\core\util\iadypage\IaDylist();
        $list_data = $ia_dylist->init()
            ->addTopButton('add', '添加菜单', ['api' => '/v1/admin/core/menu/add'])
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
            ->addColumn('id' , 'ID', ['width' => '5px'])
            ->addColumn('module', '所属模块', ['width' => '80px'])
            ->addColumn('title', '菜单标题', ['width' => '300px'])
            ->addColumn('menu_type', '类型', ['width' => '50px'])
            ->addColumn('api_method', '请求方法', ['width' => '100px'])
            ->addColumn('admin_api', '后台接口', ['minWidth' => '150px'])
            ->addColumn('is_hide', '隐藏', ['width' => '50px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('right_button_list', '操作', [
                'minWidth' => '50px',
                'type' => 'template',
                'template' => 'right_button_list'
            ])
            ->getData();

        //返回数据
        return json(['code' => 200, 'msg' => '成功', 'data' => [
            'data_list' => $menu_tree,
            'list_data' => $list_data
        ]]);
    }

    /**
     * 添加
     *
     * @return \think\Response
     */
    public function add()
    {
        if(request()->isPost()){
            //数据验证
            $validate = Validate::make([
                'module'  => 'require',
                'title' => 'require',
                'menu_type' => 'require',
                'path' => 'require',
                'api_prefix' => 'require',
                'api_method' => 'require',
            ],
            [
                'module.require' => '请选择模块',
                'title.require' => '菜单名称必须',
                'menu_type.require' => '菜单类型必须',
                'path.require' => '接口路径必须',
                'api_prefix.require' => '接口前缀必须',
                'api_method.require' => '请求方法必须',
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
            $data_db['api_method'] = implode('|', $data_db['api_method']);
            $data_db['sortnum'] = 0;
            
            //存储数据
            $ret = $this->core_menu->insert($data_db);
            if ($ret) {
                return json(['code' => 200, 'msg' => '添加成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '添加失败', 'data' => []]);
            }
        } else {
            //获取模块列表
            $module_list = $this->core_module
                ->where('status', 1)
                ->order('sortnum asc')
                ->select();
            $module_list_select = [];
            foreach ($module_list as $key => $val) {
                $module_list_select[$key]['title'] = $val['title'];
                $module_list_select[$key]['value'] = $val['name'];
            }

            //获取菜单基于标题的树状列表
            $menu_list = $this->core_menu
                ->where(['delete_time' => 0])
                ->order('sortnum asc')
                ->select();
            $tree      = new Tree();
            $menu_tree = $tree->array2tree($menu_list, 'title', 'path', 'pmenu', 0, false);
            $menu_tree_select = [];
            foreach ($menu_tree as $key1 => $val1) {
                $menu_tree_select[$key1]['title'] = $val1['title_show'];
                $menu_tree_select[$key1]['value'] = $val1['path'];
            }

            //构造动态页面数据
            $ia_dyform      = new \app\core\util\iadypage\IaDyform();
            $form_data = $ia_dyform->init()
                ->setFormMethod('post')
                ->addFormItem('module', '模块', 'select', '', '请选择模块', '模块是一个可分享使用的最小功能包', ['options' => $module_list_select])
                ->addFormItem('pmenu', '上级菜单', 'select', '', '请选择上级菜单', '请选择上级菜单', ['options' => $menu_tree_select])
                ->addFormItem('title', '菜单标题', 'text', '', '请输入菜单标题', '菜单标题是显示在左侧列表中的')
                ->addFormItem('tip', '菜单说明', 'text', '', '请输入菜单说明', '好的说明有助于用户理解')
                ->addFormItem('menu_type', '菜单类型', 'radio', '', '请选择菜单类型', '请选择菜单类型', [
                    'options' => [
                        ['title' => '分组','value' => 0,],
                        ['title' => '功能页面+接口','value' => 1,],
                        ['title' => '功能按钮+接口','value' => 2,],
                        ['title' => '纯接口','value' => 3,]
                    ]
                ])
                ->addFormItem('path', '接口路径', 'text', '', '请输入接口路径', '接口路径举例：/core/user/lists')
                ->addFormItem('api_prefix', '接口前缀', 'text', 'v1', '接口前缀', '一般默认v1')
                ->addFormItem('api_suffix', '接口参数', 'text', '', '请输入接口参数', '接口参数举例：/:id/:name')
                ->addFormItem('api_method', '请求方法', 'checkbox', '', '请勾选请求方法', '尽量符合Restful风格', [
                    'options' => [
                        ['title' => 'GET', 'value' => 'GET',],
                        ['title' => 'POST', 'value' => 'POST',],
                        ['title' => 'PUT', 'value' => 'PUT',],
                        ['title' => 'DELETE', 'value' => 'DELETE',]
                    ]
                ])
                ->addFormItem('is_iadypage', '动态页面', 'radio', 1, '请选择是否自动生成页面', '系统内容了动态页面技术，可以自动生成后台前端页面', [
                    'options' => [
                        ['title' => '是', 'value' => 1,],
                        ['title' => '否', 'value' => 0,]
                    ]
                ])
                ->addFormItem('is_hide', '是否隐藏', 'radio', 0, '请选择是否隐藏', '有时候一些功能不需要可以隐藏', [
                    'options' => [
                        ['title' => '是', 'value' => 1,],
                        ['title' => '否', 'value' => 0,]
                    ]
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
                ->addFormRule('api_method', [
                    ['required' => true, 'type' => 'array', 'min' =>  1, 'message' => '请勾选请求方法', 'trigger' => 'change'],
                ])
                ->setFormValues()
                ->getData();
            
            //返回数据
            return json(
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
     */
    public function edit($id)
    {
        if(request()->isPut()){
            //数据验证
            $validate = Validate::make([
                'module'  => 'require',
                'title' => 'require',
                'menu_type' => 'require',
                'path' => 'require',
                'api_prefix' => 'require',
                'api_method' => 'require',
            ],
            [
                'module.require' => '请选择模块',
                'title.require' => '菜单名称必须',
                'menu_type.require' => '菜单类型必须',
                'path.require' => '接口路径必须',
                'api_prefix.require' => '接口前缀必须',
                'api_method.require' => '请求方法必须',
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
            if (isset($data_db['api_method'])) {
                $data_db['api_method'] = implode('|', $data_db['api_method']);
            }

            // 存储数据
            try{
                $ret = $this->core_menu
                    ->where('id', $id)
                    ->update($data_db);
            }catch(\Exception $e){
                return json(['code' => 0, 'msg' => '修改失败' . json_encode($e), 'data' => []]);
            }
            if ($ret) {
                return json(['code' => 200, 'msg' => '修改成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '修改失败', 'data' => []]);
            }
        } else {
            //获取菜单信息
            //用户信息
            $info = $this->core_menu
                ->where('id', $id)
                ->find();
            $info['api_method'] = explode('|', $info['api_method']);

            //获取模块列表
            $module_list = $this->core_module
                ->removeOption('where')
                ->where('status', 1)
                ->order('sortnum asc')
                ->select();
            $module_list_select = [];
            foreach ($module_list as $key => $val) {
                $module_list_select[$key]['title'] = $val['title'];
                $module_list_select[$key]['value'] = $val['name'];
            }

            //获取菜单基于标题的树状列表
            $menu_list = $this->core_menu
                ->removeOption('where')
                ->where(['delete_time' => 0])
                ->order('sortnum asc')
                ->select();
            $tree      = new Tree();
            $menu_tree = $tree->array2tree($menu_list, 'title', 'path', 'pmenu', 0, false);
            $menu_tree_select = [];
            foreach ($menu_tree as $key => $val) {
                $menu_tree_select[$key]['title'] = $val['title_show'];
                $menu_tree_select[$key]['value'] = $val['path'];
            }

            //构造动态页面数据
            $ia_dyform      = new \app\core\util\iadypage\IaDyform();
            $form_data = $ia_dyform->init()
                ->setFormMethod('put')
                ->addFormItem('module', '模块', 'select', '', '请选择模块', '模块是一个可分享使用的最小功能包', ['options' => $module_list_select])
                ->addFormItem('pmenu', '上级菜单', 'select', '', '请选择上级菜单', '请选择上级菜单', ['options' => $menu_tree_select])
                ->addFormItem('title', '菜单标题', 'text', '', '请输入菜单标题', '菜单标题是显示在左侧列表中的')
                ->addFormItem('tip', '菜单说明', 'text', '', '请输入菜单说明', '好的说明有助于用户理解')
                ->addFormItem('menu_type', '菜单类型', 'radio', '', '请选择菜单类型', '请选择菜单类型', [
                    'options' => [
                        ['title' => '分组','value' => 0,],
                        ['title' => '功能页面+接口','value' => 1,],
                        ['title' => '功能按钮+接口','value' => 2,],
                        ['title' => '纯接口','value' => 3,]
                    ]
                ])
                ->addFormItem('path', '接口路径', 'text', '', '请输入接口路径', '接口路径举例：/core/user/lists')
                ->addFormItem('api_prefix', '接口前缀', 'text', 'v1', '接口前缀', '一般默认v1')
                ->addFormItem('api_suffix', '接口参数', 'text', '', '请输入接口参数', '接口参数举例：/:id/:name')
                ->addFormItem('api_method', '请求方法', 'checkbox', '', '请勾选请求方法', '尽量符合Restful风格', [
                    'options' => [
                        ['title' => 'GET', 'value' => 'GET',],
                        ['title' => 'POST', 'value' => 'POST',],
                        ['title' => 'PUT', 'value' => 'PUT',],
                        ['title' => 'DELETE', 'value' => 'DELETE',]
                    ]
                ])
                ->addFormItem('is_iadypage', '动态页面', 'radio', 1, '请选择是否自动生成页面', '系统内容了动态页面技术，可以自动生成后台前端页面', [
                    'options' => [
                        ['title' => '是', 'value' => 1,],
                        ['title' => '否', 'value' => 0,]
                    ]
                ])
                ->addFormItem('is_hide', '是否隐藏', 'radio', 0, '请选择是否隐藏', '有时候一些功能不需要可以隐藏', [
                    'options' => [
                        ['title' => '是', 'value' => 1,],
                        ['title' => '否', 'value' => 0,]
                    ]
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
                ->addFormRule('api_method', [
                    ['required' => true, 'type' => 'array', 'min' =>  1, 'message' => '请勾选请求方法', 'trigger' => 'change'],
                ])
                ->addFormRule('is_iadypage', [
                    ['required' => true, 'type' => 'number', 'message' => '请选择是否自动生成页面', 'trigger' => 'change'],
                ])
                ->addFormRule('is_hide', [
                    ['required' => true, 'type' => 'number', 'message' => '请选择是否隐藏', 'trigger' => 'change'],
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
     * 后台左侧导航列表路由规则
     *
     * @return \think\Response
     */
    public function lists()
    {
        // 计算路由
        $data_list = $this->core_menu
            ->where(['delete_time' => 0])
            ->where('menu_type', '>', 0)
            ->select();
        foreach ($data_list as $key => &$val) {
            $val['api'] = $val['api_prefix'] . '/admin' . $val['path'];
        }
        return json(['code' => 200, 'msg' => '成功', 'data' => ['data_list' => $data_list]]);
    }

    /**
     * 删除
     * 
     * @return \think\Response
     */
    public function delete($id)
    {
        // 子菜单检测
        $info = $this->core_menu
            ->removeOption('where')
            ->where(['id' => $id])
            ->find();
        $exist = $this->core_menu
            ->removeOption('where')
            ->where(['pmenu' => $info['path']])
            ->count();
        if ($exist > 0) {
            return json(['code' => 0, 'msg' => '存在子菜单无法删除', 'data' => []]);
        }
    
        $ret = $this->core_menu
            ->removeOption('where')
            ->where(['id' => $id])
            ->useSoftDelete('delete_time', time())
            ->delete();
        if ($ret) {
            return json(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return json(['code' => 0, 'msg' => '删除错误', 'data' => []]);
        }
    }
}
