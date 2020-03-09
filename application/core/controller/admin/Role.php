<?php
/**
 * +----------------------------------------------------------------------
 * | UniAdmin [ 渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2020 http://uniadmin.jiangruyi.com All rights reserved.
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
 * 角色管理
 *
 * @author jry <ijry@qq.com>
 */
class Role extends Admin
{
    private $core_role;
    private $core_menu;
    private $core_user;

    protected function initialize()
    {
        parent::initialize();

        $this->core_role = new \app\core\model\Role();
        $this->core_menu = new \app\core\model\Menu();
        $this->core_user = new \app\core\model\User();
    }

    /**
     * 角色列表
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function trees()
    {
        //角色列表
        $dataList = $this->core_role->select()->toArray();
        $tree      = new Tree();
        $dataTree = $tree->list2tree($dataList);

        //构造动态页面数据
        $xyBuilderList = new \app\core\util\xybuilder\XyBuilderList();
        $listData = $xyBuilderList->init()
            ->addTopButton('add', '添加角色', [
                'api' => '/v1/admin/core/role/add',
                'width' => '1000'
            ])
            ->addRightButton('member', '成员', [
                'modalType' => 'list',
                'api' => '/v1/admin/core/user_role/lists',
                'apiSuffix' => ['name'],
                'width' => '900',
                'title' => '角色成员'
            ])
            ->addRightButton('edit', '修改', [
                'api' => '/v1/admin/core/role/edit',
                'title' => '修改角色',
                'width' => '1000',
            ])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/core/role/delete',
                'title' => '确认要删除该角色吗？',
                'modalType' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p><p>如果该角色下有子角色需要先删除或者移动</p><p>如果该角色下有成员需要先移除才可以删除</p><p>删除该角色将会删除对应的权限数据</p></p>',
            ])
            ->addColumn('id' , 'ID', ['width' => '50px'])
            ->addColumn('title', '部门', ['width' => '350px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('rightButtonList', '操作', [
                'minWidth' => '50px',
                'type' => 'template',
                'template' => 'rightButtonList'
            ])
            ->setDataList($dataTree)
            ->getData();

        //返回数据
        return $this->return(
            [
                'code' => 200, 'msg' => '成功', 'data' => [
                    'listData' => $listData
                ]
            ]
        );
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
                'pid'  => 'number',
                'name' => 'require',
                'title' => 'require'
            ],
            [
                'pid.number' => 'pid必须数字',
                'name.require' => '名称必须',
                'title.require' => '标题必须'
            ]);
            $data = input('post.');
            if (!$validate->check($data)) {
                return $this->return(['code' => 0, 'msg' => $validate->getError(), 'data' => []]);
            }

            //数据构造
            $data_db = $data;
            if (count($data_db) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            $data_db['status'] = 1;
            $data_db['sortnum'] = isset($data_db['sortnum']) ? $data_db['sortnum'] : 0;
            $data_db['adminAuth'] = isset($data_db['adminAuth']) ? implode(',', $data_db['adminAuth']) : ''; //后台权限
            $data_db['apiAuth'] = isset($data_db['apiAuth']) ? implode(',', $data_db['apiAuth']) : ''; //接口权限

            // 存储数据
            $ret = $this->core_role->save($data_db);
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '添加角色成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '添加角色失败:' . $this->core_role->getError(), 'data' => []]);
            }
        } else {
            //获取后台权限接口
            $dataList = $this->core_menu
                ->where('menuLayer', '=', 'admin')
                ->order('sortnum asc')
                ->select()->toArray();
            foreach ($dataList as $key => &$val) {
                if ($val['menuType'] > 0) {
                    $val['adminAuth'] = '/' . $val['apiPrefix'] . '/admin' . $val['path'];
                }
            }
            $tree      = new Tree();
            $menu_tree = $tree->list2tree($dataList, 'path', 'pmenu', 'children', 0, false);

            //获取角色基于标题的树状列表
            $role_list = $this->core_role
                ->order('sortnum asc')
                ->select()->toArray();
            $tree      = new Tree();
            $role_tree = $tree->array2tree($role_list, 'title', 'id', 'pid', 0, false);
            $role_tree_select = [];
            foreach ($role_tree as $key1 => $val1) {
                $role_tree_select[$key1]['title'] = $val1['title_show'];
                $role_tree_select[$key1]['value'] = $val1['id'];
            }

            //构造动态页面数据
            $xyBuilderForm = new \app\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('post')
                ->addFormItem('pid', '上级', 'select', 0, [
                    'tip' => '选择上级后会限制权限范围不大于上级',
                    'options' => $role_tree_select,
                    'position' => 'bottom'
                ])
                ->addFormItem('name', '英文名', 'text', '', [
                    'placeholder' => '请输入英文名',
                    'tip' => '英文名其实可以理解为一个系统代号',
                    'position' => 'bottom'
                ])
                ->addFormItem('title', '角色名称', 'text', '', [
                    'placeholder' => '请输入角色名称',
                    'tip' => '角色名称也可以理解为部门名称',
                    'position' => 'bottom'
                ])
                ->addFormItem('adminAuth', '后台权限', 'checkboxtree', '',
                     [
                    'tip' => '勾选角色权限',
                    'columns' => [
                        ['title' => '菜单(接口)', 'key' => 'title', 'minWidth' => '150px'],
                        ['title' => '说明', 'key' => 'tip'],
                        ['title' => '接口', 'key' => 'adminAuth'],
                        ['title' => '类型', 'key' => 'menuType', 'width' => '40px']
                    ],
                    'data' => $menu_tree,
                    'expandKey' => 'title',
                    'position' => 'bottom'
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
            return $this->return(
                [
                    'code' => 200,
                    'msg' => '成功',
                    'data' => [
                        'formData' => $formData
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
            if ($id == 1) {
                return $this->return(['code' => 0,'msg' => '超级管理员角色不允许修改','data' => []]);
            }

            // 数据验证
            $validate = Validate::make([
                'pid'  => 'number',
                'name' => 'require',
                'title' => 'require'
            ],
            [
                'pid.number' => 'pid必须数字',
                'name.require' => '名称必须',
                'title.require' => '标题必须'
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
            if (isset($data_db['adminAuth']) && is_array($data_db['adminAuth'])) {
                $data_db['adminAuth'] = implode(',', $data_db['adminAuth']);
            }
            if (isset($data_db['apiAuth']) && is_array($data_db['apiAuth'])) {
                $data_db['apiAuth'] = implode(',', $data_db['apiAuth']);
            }

            // 存储数据
            $ret = $this->core_role->update($data_db, ['id' => $id]);
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '修改角色成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '修改角色失败:' . $this->core_role->getError(), 'data' => []]);
            }
        } else {
            //获取角色信息
            $info = $this->core_role
                ->where('id', $id)
                ->find();
            $info['adminAuth'] = explode(',', $info['adminAuth']);

            //获取后台权限接口
            $dataList = $this->core_menu
                ->where('menuLayer', '=', 'admin')
                ->order('sortnum asc')
                ->select()->toArray();
            foreach ($dataList as $key => &$val) {
                if ($val['menuType'] > 0) {
                    $val['adminAuth'] = '/' . $val['apiPrefix'] . '/admin' . $val['path'];
                    //超级管理员拥有所有权限
                    if (in_array($val['adminAuth'], $info['adminAuth']) || $id == 1) {
                        $val['_isChecked'] = true;
                    }
                }
            }
            $tree      = new Tree();
            $menu_tree = $tree->list2tree($dataList, 'path', 'pmenu', 'children', 0, false);

            //获取角色基于标题的树状列表
            $role_list = $this->core_role
                ->order('sortnum asc')
                ->select()->toArray();
            $tree      = new Tree();
            $role_tree = $tree->array2tree($role_list, 'title', 'id', 'pid', 0, false);
            $role_tree_select = [];
            foreach ($role_tree as $key1 => $val1) {
                $role_tree_select[$key1]['title'] = $val1['title_show'];
                $role_tree_select[$key1]['value'] = $val1['id'];
            }

            //构造动态页面数据
            $xyBuilderForm = new \app\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('put')
               ->addFormItem('pid', '上级', 'select', 0, [
                    'tip' => '选择上级后会限制权限范围不大于上级',
                    'options' => $role_tree_select,
                    'position' => 'bottom'
                ])
                ->addFormItem('name', '英文名', 'text', '', [
                    'placeholder' => '请输入英文名',
                    'tip' => '英文名其实可以理解为一个系统代号',
                    'position' => 'bottom'
                ])
                ->addFormItem('title', '角色名称', 'text', '', [
                    'placeholder' => '请输入角色名称',
                    'tip' => '角色名称也可以理解为部门名称',
                    'position' => 'bottom'
                ])
                ->addFormItem('adminAuth', '后台权限', 'checkboxtree', '',
                     [
                    'tip' => '勾选角色权限',
                    'columns' => [
                        ['title' => '菜单(接口)', 'key' => 'title', 'minWidth' => '150px'],
                        ['title' => '说明', 'key' => 'tip'],
                        ['title' => '接口', 'key' => 'adminAuth'],
                        ['title' => '类型', 'key' => 'menuType', 'width' => '40px']
                    ],
                    'data' => $menu_tree,
                    'expand-key' => 'title',
                    'position' => 'bottom'
                ])
                ->addFormItem('sortnum', '排序', 'text', '')
                ->addFormRule('name', [
                    ['required' => true, 'message' => '请填写角色英文名称', 'trigger' => 'change'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写角色名称', 'trigger' => 'change'],
                ])
                ->setFormValues($info)
                ->getData();

            //返回数据
            return $this->return([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'formData' => $formData
                ]
            ]);
        }
    }

    /**
     * 删除
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function delete($id)
    {
        if ($id == 1) {
            return $this->return(['code' => 0,'msg' => '超级管理员角色不允许删除','data' => []]);
        }
        $ret = $this->core_role
            ->where('id', $id)
            ->find()
            ->delete();
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return $this->return(['code' => 0, 'msg' => '删除错误:' . $this->core_role->getError(), 'data' => []]);
        }
    }
}
