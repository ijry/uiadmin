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
 * 角色
 */
class Role extends Admin
{
    private $core_role;
    private $core_menu;
    private $core_user;

    protected function initialize()
    {
        parent::initialize();
        $this->core_role = Db::name('core_role');
        $this->core_menu = Db::name('core_menu');
        $this->core_user = Db::name('core_user');
    }

    /**
     * 角色列表
     *
     * @return \think\Response
     */
    public function trees()
    {
        //角色列表
        $data_list = $this->core_role
            ->where(['delete_time' => 0])
            ->select();
        $tree      = new Tree();
        $data_tree = $tree->list2tree($data_list);

        //构造动态页面数据
        $ia_dylist      = new \app\core\util\iadypage\IaDylist();
        $list_data = $ia_dylist->init()
            ->addTopButton('add', '添加角色', ['api' => '/v1/admin/core/role/add'])
            ->addRightButton('member', '成员', [
                'modal_type' => 'list',
                'api' => '/v1/admin/core/user_role/lists',
                'api_suffix' => ['name'],
                'width' => '900',
                'title' => '角色成员'
            ])
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/core/role/edit', 'title' => '修改角色'])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/core/role/delete',
                'title' => '确认要删除该角色吗？',
                'modal_type' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p><p>如果该角色下有子角色需要先删除或者移动</p><p>如果该角色下有成员需要先移除才可以删除</p><p>删除该角色将会删除对应的权限数据</p></p>',
            ])
            ->addColumn('id' , 'ID', ['width' => '50px'])
            ->addColumn('title', '部门', ['minWidth' => '100px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('right_button_list', '操作', [
                'minWidth' => '50px',
                'type' => 'template',
                'template' => 'right_button_list'
            ])
            ->getData();
        
        //返回数据
        return json(
            [
                'code' => 200, 'msg' => '成功', 'data' => [
                    'data_list' => $data_tree,
                    'list_data' => $list_data
                ]
            ]
        );
    }

    /**
     * 添加
     *
     * @return \think\Response
     */
    public function add()
    {
        if(request()->isPost()){
            // 数据验证
            $validate = Validate::make([
                'pid'  => 'number',
                'name' => 'require',
                'title' => 'require'
            ],
            [
                'pid.number' => 'pid必须数字',
                'name.require' => '用户名必须',
                'title.require' => '密码必须'
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
            $data_db['status'] = 1;
            $data_db['sortnum'] = isset($data_db['sortnum']) ? $data_db['sortnum'] : 0;
            $data_db['admin_auth'] = isset($data_db['admin_auth']) ? implode(',', $data_db['admin_auth']) : ''; //后台权限
            $data_db['api_auth'] = isset($data_db['api_auth']) ? implode(',', $data_db['api_auth']) : ''; //接口权限
            
            // 存储数据
            $ret = $this->core_role->insert($data_db);
            if ($ret) {
                return json(['code' => 200, 'msg' => '添加角色成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '添加角色失败', 'data' => []]);
            }
        } else {
            //获取后台权限接口
            $data_list = $this->core_menu
                ->removeOption('where')
                ->where(['delete_time' => 0])
                ->order('sortnum asc')
                ->select();
            foreach ($data_list as $key => &$val) {
                if ($val['menu_type'] > 0) {
                    $val['admin_auth'] = '/' . $val['api_prefix'] . '/admin' . $val['path'];
                }
            }
            $tree      = new Tree();
            $menu_tree = $tree->list2tree($data_list, 'path', 'pmenu', 'children', 0, false);

            //获取角色基于标题的树状列表
            $role_list = $this->core_role
                ->removeOption('where')
                ->where(['delete_time' => 0])
                ->order('sortnum asc')
                ->select();
            $tree      = new Tree();
            $role_tree = $tree->array2tree($role_list, 'title', 'id', 'pid', 0, false);
            $role_tree_select = [];
            foreach ($role_tree as $key1 => $val1) {
                $role_tree_select[$key1]['title'] = $val1['title_show'];
                $role_tree_select[$key1]['value'] = $val1['id'];
            }

            //构造动态页面数据
            $ia_dyform      = new \app\core\util\iadypage\IaDyform();
            $form_data = $ia_dyform->init()
                ->setFormMethod('post')
                ->addFormItem('pid', '上级', 'select', 0, '请选择上级', '选择上级后会限制权限范围不大于上级', ['options' => $role_tree_select])
                ->addFormItem('name', '英文名', 'text', '', '请输入英文名', '英文名其实可以理解为一个系统代号')
                ->addFormItem('title', '角色名称', 'text', '', '请输入角色名称', '角色名称也可以理解为部门名称')
                ->addFormItem('admin_auth', '后台权限', 'checkboxtree', '', '请勾选该角色的权限', '',[
                    'columns' => [
                        ['title' => '菜单(接口)', 'key' => 'title', 'minWidth' => '150px'],
                        ['title' => '说明', 'key' => 'tip'],
                        ['title' => '接口', 'key' => 'admin_auth'],
                        ['title' => '类型', 'key' => 'menu_type', 'width' => '40px']
                    ],
                    'data' => $menu_tree,
                    'expand-key' => 'title'
                ])
                ->addFormRule('name', [
                    ['required' => true, 'message' => '请填写角色英文名称', 'trigger' => 'change'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写角色名称', 'trigger' => 'change'],
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
            if ($id == 1) {
                return json(['code' => 0,'msg' => '超级管理员角色不允许修改','data' => []]);
            }

            // 数据验证
            $validate = Validate::make([
                'pid'  => 'number',
                'name' => 'require',
                'title' => 'require'
            ],
            [
                'pid.number' => 'pid必须数字',
                'name.require' => '用户名必须',
                'title.require' => '密码必须'
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
            if (isset($data_db['admin_auth']) && is_array($data_db['admin_auth'])) {
                $data_db['admin_auth'] = implode(',', $data_db['admin_auth']);
            }
            if (isset($data_db['api_auth']) && is_array($data_db['api_auth'])) {
                $data_db['api_auth'] = implode(',', $data_db['api_auth']);
            }

            // 存储数据
            $ret = $this->core_role
                ->removeOption('where')
                ->where('id', $id)
                ->update($data_db);
            if ($ret) {
                return json(['code' => 200, 'msg' => '修改角色成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '修改角色失败', 'data' => []]);
            }
        } else {
            //获取角色信息
            $info = $this->core_role
                ->removeOption('where')
                ->where('id', $id)
                ->find();
            $info['admin_auth'] = explode(',', $info['admin_auth']);

            //获取后台权限接口
            $data_list = $this->core_menu
                ->order('sortnum asc')
                ->select();
            foreach ($data_list as $key => &$val) {
                if ($val['menu_type'] > 0) {
                    $val['admin_auth'] = '/' . $val['api_prefix'] . '/admin' . $val['path'];
                    //超级管理员拥有所有权限
                    if (in_array($val['admin_auth'], $info['admin_auth']) || $id == 1) {
                        $val['_isChecked'] = true;
                    }
                }
            }
            $tree      = new Tree();
            $menu_tree = $tree->list2tree($data_list, 'path', 'pmenu', 'children', 0, false);

            //获取角色基于标题的树状列表
            $role_list = $this->core_role
                ->removeOption('where')
                ->where(['delete_time' => 0])
                ->order('sortnum asc')
                ->select();
            $tree      = new Tree();
            $role_tree = $tree->array2tree($role_list, 'title', 'id', 'pid', 0, false);
            $role_tree_select = [];
            foreach ($role_tree as $key1 => $val1) {
                $role_tree_select[$key1]['title'] = $val1['title_show'];
                $role_tree_select[$key1]['value'] = $val1['id'];
            }

            //构造动态页面数据
            $ia_dyform      = new \app\core\util\iadypage\IaDyform();
            $form_data = $ia_dyform->init()
                ->setFormMethod('put')
                ->addFormItem('pid', '上级', 'select', $info['pid'], '请选择上级', '选择上级后会限制权限范围不大于上级', ['options' => $role_tree_select])
                ->addFormItem('name', '英文名', 'text', $info['name'], '请输入英文名', '英文名其实可以理解为一个系统代号')
                ->addFormItem('title', '角色名称', 'text', $info['title'], '请输入角色名称', '角色名称也可以理解为部门名称')
                ->addFormItem('admin_auth', '后台权限', 'checkboxtree', $info['admin_auth'], '请勾选该角色的权限', '',[
                    'columns' => [
                        ['title' => '菜单(接口)', 'key' => 'title', 'minWidth' => '150px'],
                        ['title' => '说明', 'key' => 'tip'],
                        ['title' => '接口', 'key' => 'admin_auth'],
                        ['title' => '类型', 'key' => 'menu_type', 'width' => '40px']
                    ],
                    'data' => $menu_tree,
                    'expand-key' => 'title'
                ])
                ->addFormItem('sortnum', '排序', 'text', $info['sortnum'], '排序', '排序')
                ->addFormRule('name', [
                    ['required' => true, 'message' => '请填写角色英文名称', 'trigger' => 'change'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写角色名称', 'trigger' => 'change'],
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
     * 删除
     * 
     * @return \think\Response
     */
    public function delete($id)
    {
        if ($id == 1) {
            return json(['code' => 0,'msg' => '超级管理员角色不允许删除','data' => []]);
        }
        $ret = $this->core_role
            ->where(['id' => $id])
            ->useSoftDelete('delete_time', time())
            ->delete();
        if ($ret) {
            return json(['code' => 200,'msg' => '删除成功','data' => []]);
        } else {
            return json(['code' => 200,'msg' => '删除错误','data' => []]);
        }
    }
}
