<?php
/**
 * +----------------------------------------------------------------------
 * | xycloud [ 多租户渐进式后台云 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2020 http://jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
 * | 版权申明：此源码不是一个自由软件，是jry推出的私有源码，严禁在未经许可的情况下
 * | 拷贝、复制、传播、使用此源码的任意代码，如有违反，请立即删除，否则您将面临承担相应
 * | 法律责任的风险。如果需要取得官方授权，请联系官方QQ598821125。
*/
namespace uiadmin\auth\admin;


use Illuminate\Support\Facades\Request;
use uiadmin\core\admin\BaseAdmin;
use uiadmin\auth\model\User as UserModel;
use uiadmin\auth\model\Menu as MenuModel;
use uiadmin\auth\model\Role as RoleModel;
use uiadmin\core\util\Tree;

/**
 * 角色管理
 *
 * @author jry <ijry@qq.com>
 */
class Role extends BaseAdmin
{
    /**
     * 角色列表
     *
     * @return \Response
     * @author jry <ijry@qq.com>
     */
    public function trees()
    {
        // 角色列表
        $dataList = RoleModel::get()->toArray();
        $tree      = new Tree();
        $dataTree = $tree->list2tree($dataList);

        // 构造动态页面数据
        $xyBuilderList = new \uiadmin\core\util\xybuilder\XyBuilderList();
        $listData = $xyBuilderList->init()
            ->addTopButton('add', '添加部门', [
                'pageType' => 'modal',
                'api' => '/v1/admin/auth/role/add',
                'width' => '1000'
            ])
            ->addRightButton('member', '成员', [
                'pageType' => 'modal',
                'modalType' => 'list',
                'api' => '/v1/admin/auth/user_role/lists',
                'apiSuffix' => ['name'],
                'width' => '900',
                'title' => '角色成员'
            ])
            ->addRightButton('edit', '修改', [
                'pageType' => 'modal',
                'api' => '/v1/admin/auth/role/edit',
                'title' => '修改角色',
                'width' => '1000'
            ])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/auth/role/delete',
                'title' => '确认要删除该角色吗？',
                'modalType' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p><p>如果该角色下有子角色需要先删除或者移动</p><p>如果该角色下有成员需要先移除才可以删除</p><p>删除该角色将会删除对应的权限数据</p></p>',
            ])
            ->addColumn('id' , 'ID', ['minWidth' => '15px'])
            ->addColumn('name', '标识', ['width' => '200px'])
            ->addColumn('title', '部门名称', ['width' => '250px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('status' , '状态', [
                'width' => '80px',
                'type' => 'template',
                'template' => 'switch',
                'options' => [ 1 => '正常', 0 => '禁用']
            ])
            ->addColumn('rightButtonList', '操作', [
                'minWidth' => '100px',
                'type' => 'template',
                'template' => 'rightButtonList'
            ])
            ->setConfig('listExpandAll', true)
            ->setTableName('xy_auth_role')
            ->setDataList($dataTree)
            ->getData();

        // 返回数据
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
     * @return \Response
     * @author jry <ijry@qq.com>
     */
    public function add()
    {
        if(Request::isMethod('post')){
            // 数据验证
            $this->validateMake([
                'pid'  => 'number',
                'name' => 'required',
                'title' => 'require'
            ],
            [
                'pid.number' => 'pid必须数字',
                'name.require' => '名称必须',
                'title.require' => '标题必须'
            ]);
            $data = Request::input();
            $this->validateData($data);

            // 数据构造
            $dataDb = $data;
            if (count($dataDb) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            $dataDb['status'] = 1;
            $dataDb['sortnum'] = isset($dataDb['sortnum']) ? $dataDb['sortnum'] : 0;

            // 存储数据
            $ret = RoleModel::create($dataDb);
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '添加角色成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '添加角色失败:' . $this->core_role->getError(), 'data' => []]);
            }
        } else {
            // 获取后台权限接口
            $dataList = MenuModel::where('menu_layer', '=', 'admin')
                ->orderBy('sortnum')
                ->get()->toArray();
            foreach ($dataList as $key => &$val) {
                $val['adminAuth'] = '/' . $val['api_prefix'] . '/admin' . $val['path'];
            }
            $tree      = new Tree();
            $menuTree = $tree->list2tree($dataList, 'path', 'pmenu', 'children', 0, false);

            // 获取角色基于标题的树状列表
            $roleList = RoleModel::orderBy('sortnum')
                ->select();
            $tree      = new Tree();
            $roleTree = $tree->array2tree($roleList, 'title', 'id', 'pid', 0, false);
            $roleTreeSelect = [];
            foreach ($roleTree as $key1 => $val1) {
                $roleTreeSelect[$key1]['title'] = $val1['title_show'];
                $roleTreeSelect[$key1]['value'] = $val1['id'];
            }

            // 构造动态页面数据
            $xyBuilderForm = new \uiadmin\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('post')
                ->addFormItem('pid', '上级', 'select', 0, [
                    'tip' => '选择上级后会限制权限范围不大于上级',
                    'options' => $roleTreeSelect,
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
                ->addFormItem('policys', '后台权限', 'checkboxtree', [],
                     [
                    'tip' => '勾选角色权限',
                    'columns' => [
                        ['title' => '菜单(接口)', 'name' => 'title', 'minWidth' => '150px'],
                        ['title' => '接口', 'name' => 'adminAuth'],
                        ['title' => '类型', 'name' => 'menuType', 'width' => '40px'],
                        ['title' => '说明', 'name' => 'tip'],
                    ],
                    'data' => $menuTree,
                    'nodeKey' => 'adminAuth',
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

            // 返回数据
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
     * @return \Response
     * @author jry <ijry@qq.com>
     */
    public function edit($id)
    {

        // 获取角色信息
        $info = RoleModel::where('id', $id)
            ->first();
        if(Request::isMethod('put')){
            if ($id == 1) {
                return $this->return(['code' => 0,'msg' => '超级管理员角色不允许修改','data' => []]);
            }

            // 数据验证
            $this->validateMake([
                'pid'  => 'number',
                'name' => 'required',
                'title' => 'require'
            ],
            [
                'pid.number' => 'pid必须数字',
                'name.require' => '名称必须',
                'title.require' => '标题必须'
            ]);
            $data = Request::input();
            $this->validateData($data);

            // 数据构造
            $dataDb = $data;
            if (count($dataDb) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }

            // 更新数据
            foreach ($dataDb as $key => $value) {
                if (isset($info[$key])) {
                    $info[$key] = $value;
                }
            }

            // 存储数据
            $ret = $info->save();
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '修改角色成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '修改角色失败', 'data' => []]);
            }
        } else {
            // 获取后台权限接口
            $dataList = MenuModel::where('menu_layer', '=', 'admin')
                ->orderBy('sortnum')
                ->get()->toArray();
            $all = [];
            foreach ($dataList as $key => &$val) {
                $val['adminAuth'] = '/' . $val['api_prefix'] . '/admin' . $val['path'];
                // 超级管理员拥有所有权限
                if ($id == 1) {
                    $all[] = $val['adminAuth'];
                }
            }
            if ($id == 1) {
                $info['adminAuth'] = $all;
            }
            $tree      = new Tree();
            $menuTree = $tree->list2tree($dataList, 'path', 'pmenu', 'children', 0, false);

            // 获取角色基于标题的树状列表
            $roleList = RoleModel::orderBy('sortnum')
                ->get()->toArray();
            $tree      = new Tree();
            $roleTree = $tree->array2tree($roleList, 'title', 'id', 'pid', 0, false);
            $roleTreeSelect = [];
            foreach ($roleTree as $key1 => $val1) {
                $roleTreeSelect[$key1]['title'] = $val1['title_show'];
                $roleTreeSelect[$key1]['value'] = $val1['id'];
            }

            // 构造动态页面数据
            $xyBuilderForm = new \uiadmin\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('put')
               ->addFormItem('pid', '上级', 'select', 0, [
                    'tip' => '选择上级后会限制权限范围不大于上级',
                    'options' => $roleTreeSelect,
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
                ->addFormItem('policys', '后台权限', 'checkboxtree', [],
                     [
                    'tip' => '勾选角色权限',
                    'columns' => [
                        ['title' => '菜单(接口)', 'name' => 'title', 'minWidth' => '150px'],
                        ['title' => '接口', 'name' => 'adminAuth'],
                        ['title' => '类型', 'name' => 'menuType', 'width' => '40px'],
                        ['title' => '说明', 'name' => 'tip'],
                    ],
                    'data' => $menuTree,
                    'nodeKey' => 'adminAuth',
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

            // 返回数据
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
     * @return \Response
     * @author jry <ijry@qq.com>
     */
    public function delete($id)
    {
        if ($id == 1) {
            return $this->return(['code' => 0,'msg' => '超级管理员角色不允许删除','data' => []]);
        }
        $ret = RoleModel::where('id', $id)
            ->first()
            ->delete();
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return $this->return(['code' => 0, 'msg' => '删除错误:' . $this->core_role->getError(), 'data' => []]);
        }
    }
}
