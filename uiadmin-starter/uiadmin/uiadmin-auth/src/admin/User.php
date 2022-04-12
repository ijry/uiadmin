<?php
/**
 * +----------------------------------------------------------------------
 * | uiadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/


namespace uiadmin\auth\admin;

use uiadmin\core\admin\BaseAdmin;
use uiadmin\core\util\Tree;
use uiadmin\auth\model\Role as RoleModel;
use uiadmin\auth\model\User as UserModel;

/**
 * 用户管理
 *
 * @author jry <ijry@qq.com>
 */
class User extends BaseAdmin
{
    /**
     * 用户列表
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function lists()
    {
        $page = input('get.page/d') ?: 1;
        $limit = input('get.limit/d') ?: 10;
        $where = [];
        $keyword = input('get.keyword', '');
        $where[] = ['nickname|username|id', 'like', '%' . $keyword . '%'];

        // 用户列表
        $dataList = UserModel::where($where)
            ->page($page, $limit)
            ->order('id desc')
            ->select();
        $total = UserModel::where($where)
            ->count();
        // foreach ($dataList as $key => &$value) {
        //     $tmp1 = $this->core_identity
        //         ->where('uid', $value->id)
        //         ->where('identityType', 'mobile')
        //         ->find();
        //     if ($tmp1) {
        //         $value['mobile'] = $tmp1->identifier;
        //     } else {
        //         $value['mobile'] = '--';
        //     }
        //     unset($tmp1);
        //     $tmp2 = $this->core_identity
        //         ->where('uid', $value->id)
        //         ->where('identityType', 'email')
        //         ->find();
        //     if ($tmp2) {
        //         $value['email'] = $tmp2->identifier;
        //     } else {
        //         $value['email'] = '--';
        //     }
        //     if (!$value['avatar']) {
        //         $value['avatar'] = "";
        //     }
        //     unset($tmp2);
        // }
        $tree      = new Tree();
        $dataList = $tree->list2tree($dataList->toArray());

        // 角色
        $roleCols = RoleModel::where('status', 1)->column('id,title', 'name');

        // 构造动态页面数据
        $xyBuilderList = new \uiadmin\core\util\xybuilder\XyBuilderList();
        $xyBuilderList->init()
            ->setDataPage($total, $limit, $page)
            ->addTopButton('add', '添加用户', ['api' => '/v1/admin/auth/user/add']);
        $listData = $xyBuilderList->addRightButton('info', '查看', [
                'api' => '/v1/admin/auth/user/info',
                'title' => '用户详情',
                'modalType' => 'tab',
                'width' => '1000'
            ])
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/auth/user/edit', 'title' => '修改用户信息'])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/auth/user/delete',
                'title' => '确认要删除该用户吗？',
                'modalType' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p>删除后将清空绑定的所有登录验证记录</p>',
            ])
            ->addFilterItem('keyword', '关键字', 'text', $keyword, [
            ])
            ->addColumn('id' , 'ID', ['width' => '80px'])
            ->addColumn('avatar' , '头像', [
                'width' => '60px',
                'type' => 'template',
                'template' => 'image',
                'options' => []
            ])
            ->addColumn('nickname', '昵称', ['width' => '120px'])
            ->addColumn('username', '用户名', ['width' => '130px'])
            ->addColumn('mobile', '手机号', ['width' => '100px'])
            ->addColumn('email', '邮箱', ['width' => '140px'])
            ->addColumn('createTime', '注册时间', [
                'width' => '170px',
                'type' => 'template',
                'template' => 'time',
                'extend' => ['format' => 'yyyy-MM-dd hh:mm:ss']
            ])
            ->addColumn('roles', '角色', [
                'width' => '120px',
                'type' => 'template',
                'template' => 'tags',
                'options' => $roleCols
            ])
            ->addColumn('status' , '状态', [
                'width' => '80px',
                'type' => 'template',
                'template' => 'switch',
                'options' => [ 1 => '正常', 0 => '禁用']
            ])
            ->addColumn('rightButtonList', '操作', [
                'minWidth' => '260px',
                'type' => 'template',
                'template' => 'rightButtonList'
            ])
            ->setTableName('auth_user')
            ->setDataList($dataList)
            ->getData();

        // 返回数据
        return json([
            'code' => 200, 'msg' => '成功', 'data' => [
                'listData' => $listData
            ]
        ]);
    }

    /**
     * 详情
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function info($id)
    {
        // 用户信息
        $info = UserModel::where('id', $id)
            ->find();

        // 构造动态页面数据
        $xyBuilderInfo = new \uiadmin\core\util\xybuilder\XyBuilderInfo();
        $infoData = $xyBuilderInfo->init()
            ->addInfoGroup([
                // 'title' => '基本',
                'data' => [
                    ['type' => 'text', 'title' => 'UID', 'value' => $info['id']],
                    ['type' => 'text', 'title' => '昵称', 'value' => $info['nickname']],
                    ['type' => 'text', 'title' => '用户名', 'value' => $info['username']],
                    ['type' => 'text', 'title' => '手机号','value' => $info['mobile']],
                    ['type' => 'text', 'title' => '邮箱','value' => $info['email']],
                    ['type' => 'image', 'title' => '头像', 'value' => $info['avatar']],
                    ['type' => 'text', 'title' => '状态', 'value' => $info['status']],
                    ['type' => 'text', 'title' => '角色','value' => $info['roles']],
                ]
            ])
            ->getData();

        // 获取其他模块需要展示在用户详情里面的，比如收货地址、邀请记录等等
        $tabList = [];
        // $hasUserInfoHook = $this->core_config
        //     ->where('name', '=', 'hookList')
        //     ->where('', 'EXP', "FIND_IN_SET('userInfoApi', value)")
        //     ->field('id,module,name,title,value')
        //     ->select();
        // if ($hasUserInfoHook) {
        //     foreach ($hasUserInfoHook as $key => $value) {
        //         $class = '\\app\\' . $value['module'] . '\\service\\Index';
        //         $service = new $class();
        //         $list = $service->getUserInfoApi($id, 'admin');
        //         $title = $list[0]['title'];
        //         $list[0]['title'] = '';
        //         $tabList[$value['module']] = [
        //             'title' => $title,
        //             'list' => $list
        //         ];
        //     }
        // }

        // 动态TAB
        $xyBuilderTab = new \uiadmin\core\util\xybuilder\XyBuilderTab();
        $tabData = $xyBuilderTab->init()
            ->addTab('基本', [
                [
                    'title' => '',
                    'pageData' => [
                        'modalType' => 'info',
                        'apiBlank' => '',
                        'api'  => '',
                        'show' => false,
                        'apiParams' => ''
                    ],
                    'predata' => $infoData
                ]
            ])
            ->addTabs($tabList)
            ->getData();

        // 返回数据
        return json([
            'code' => 200,
            'msg' => '成功',
            'data' => [
                'tabData' => $tabData
            ]
        ]);
    }

    /**
     * 添加
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function add()
    {
        if (request()->isPost()) {
            // 数据验证
            $this->validateMake([
                'nickname'  => 'require',
                'username' => 'require',
                'password' => 'require'
            ],
            [
                'nickname.require' => '昵称必须',
                'username.require' => '用户名必须',
                'password.require' => '密码必须'
            ]);
            $data = input('post.');
            $this->validate($data);

            // 数据构造
            $dataDb = $data;
            if (count($dataDb) <= 0 ) {
                return json(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            $dataDb['userKey'] = \uiadmin\core\util\Str::random(16); //秘钥
            $dataDb['password'] = user_md5($dataDb['password'], $dataDb['userKey']); // 密码不能明文需要加密存储
            $dataDb['avatar'] = isset($dataDb['avatar']) ? $dataDb['avatar'] : '';
            $dataDb['status']   = 1;
            $dataDb['createTime'] = date('Y-m-d H:i:s');
            $dataDb['lastLoginTime'] = date('Y-m-d H:i:s');

            // 存储数据
            $ret = UserModel::create($dataDb);
            if ($ret) {
                return json(['code' => 200, 'msg' => '添加用户成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '添加用户失败', 'data' => []]);
            }
        } else {
            // 获取角色基于标题的树状列表
            $roleList = RoleModel::order('sortnum asc')
                ->select();
            $tree      = new Tree();
            $roleTree = $tree->array2tree($roleList, 'title', 'id', 'pid', 0, false);
            $roleTreeSelect = [
                -1 => [
                    'title' => '前台用户',
                    'value' => 'home'
                ]
            ];
            foreach ($roleTree as $key1 => $val1) {
                $roleTreeSelect[$key1]['title'] = $val1['title_show'];
                $roleTreeSelect[$key1]['value'] = $val1['name'];
            }

            // 构造动态页面数据
            $xyBuilderForm = new \uiadmin\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('post')
                ->addFormItem('roles', '部门角色', 'selects', [], [
                    'placeholder' => '前勾选用户部门角色',
                    'options' => $roleTreeSelect
                ])
                ->addFormItem('nickname', '昵称', 'text', '', [
                    'placeholder' => '请输入昵称',
                    'tip' => '昵称类似微信昵称可以重复不能用来登录系统'
                ])
                ->addFormItem('username', '用户名', 'text', '', [
                    'placeholder' => '请输入用户名',
                    'tip' => '用户名唯一不重复，可以用来登录系统'
                ])
                ->addFormItem('password', '密码', 'password', '', [
                    'placeholder' => '请输入用户密码',
                    'tip' => '密码必须要包含字母数字和符号中的两种'
                ])
                ->addFormRule('nickname', [
                    ['required' => true, 'message' => '请填写昵称', 'trigger' => 'change'],
                ])
                ->addFormRule('username', [
                    ['required' => true, 'message' => '请填写用户名', 'trigger' => 'change'],
                ])
                ->addFormRule('password', [
                    ['required' => true, 'message' => '请填写密码', 'trigger' => 'change'],
                ])
                ->setFormValues()
                ->getData();

            // 返回数据
            return json([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'formData' => $formData
                ]
            ]);
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
        // 用户信息
        $info = UserModel::where('id', $id)
            ->find();
        if (request()->isPut()) {
            // 数据验证
            $this->validateMake([
                    'nickname'  => 'require',
                    'username' => 'require',
                ],
                [
                    'nickname.require' => '昵称必须',
                    'username.require' => '用户名必须',
                ]);
            $data = input('post.');
            $this->validate($data);

            // 数据构造
            $dataDb = $data;
            if (count($dataDb) <= 0 ) {
                return json(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            if (isset($dataDb['password']) && $dataDb['password'] != '') {
                $dataDb['userKey'] = \uiadmin\core\util\Str::random(16); //秘钥
                $dataDb['password'] = user_md5($dataDb['password'], $dataDb['userKey']); // 密码不能明文需要加密存储
            } else {
                unset($dataDb['password']);
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
                return json(['code' => 200, 'msg' => '修改用户信息成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '修改用户信息失败', 'data' => []]);
            }
        } else {
            // 获取角色基于标题的树状列表
            $roleList = RoleModel::order('sortnum asc')
                ->select();
            $tree      = new Tree();
            $roleTree = $tree->array2tree($roleList, 'title', 'id', 'pid', 0, false);
            $roleTreeSelect = [
                -1 => [
                    'title' => '前台用户',
                    'value' => 'home'
                ]
            ];
            foreach ($roleTree as $key1 => $val1) {
                $roleTreeSelect[$key1]['title'] = $val1['title_show'];
                $roleTreeSelect[$key1]['value'] = $val1['name'];
            }

            // 构造动态页面数据
            $xyBuilderForm = new \uiadmin\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('put')
                ->addFormItem('roles', '部门角色', 'selects', $info['roles'], [
                    'placeholder' => '前勾选用户部门角色',
                    'options' => $roleTreeSelect
                ])
                ->addFormItem('nickname', '昵称', 'text', $info['nickname'], [
                    'placeholder' => '请输入昵称',
                    'tip' => '昵称类似微信昵称可以重复不能用来登录系统'
                ])
                ->addFormItem('username', '用户名', 'text', $info['username'], [
                    'placeholder' => '请输入用户名',
                    'tip' => '用户名唯一不重复，可以用来登录系统'
                ])
                ->addFormItem('password', '密码', 'password', '', [
                    'placeholder' => '请输入用户密码',
                    'tip' => '密码必须要包含字母数字和符号中的两种'
                ])
                ->addFormRule('nickname', [
                    ['required' => true, 'message' => '请填写昵称', 'trigger' => 'change'],
                ])
                ->addFormRule('username', [
                    ['required' => true, 'message' => '请填写用户名', 'trigger' => 'change'],
                ])
                ->setFormValues()
                ->getData();

            // 返回数据
            return json([
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
        // 注销登录信息

        // 删除用户
        $ret = UserModel::where(['id' => $id])
            ->delete();
        if ($ret) {
            return json(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return json(['code' => 0, 'msg' => '删除错误', 'data' => []]);
        }
    }
}
