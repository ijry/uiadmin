<?php
// +----------------------------------------------------------------------
// | tpvue [ 模块化渐进式后台 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2019 http://tpvue.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------

namespace tpvue\core\controller\admin;

use think\Db;
use think\facade\Request;
use tpvue\core\controller\admin\Admin;
use tpvue\core\util\Tree;

/**
 * 用户
 */
class User extends Admin
{
    /**
     * 角色列表
     *
     * @return \think\Response
     */
    public function lists()
    {
        $data_list = Db::name('core_user')
            ->where(['delete_time' => 0])
            ->select();
        $tree      = new Tree();
        $data_list = $tree->list2tree($data_list);
        return json(
            [
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'data_list' => $data_list,
                    'dynamic_data' => [
                        'top_button_list' => [
                            'add' => [
                                'api' => 'v1/core/admin/user/add',
                                'title' => '添加用户',
                                'modalTitle' => '添加用户',
                                'width' => '600',
                                'type' => 'default',
                                'size' => '',
                                'shape' => '',
                                'icon' => ''
                            ]
                        ],
                        'right_button_list' => [
                            'edit' => [
                                'api' => 'v1/core/admin/user/edit',
                                'title' => '修改',
                                'modalTitle' => '修改用户信息',
                                'width' => '600',
                                'type' => 'default',
                                'size' => '',
                                'shape' => '',
                                'icon' => ''
                            ],
                            'delete' => [
                                'api' => 'v1/core/admin/auth_role/delete',
                                'title' => '删除',
                                'modalType' => 'confirm',
                                'modalTitle' => '确认要删除该用户吗？',
                                'okText' => '确认删除',
                                'cancelText' => '取消操作',
                                'content' => '<p><p>删除后将清空绑定的所有登录验证记录</p></p>',
                                'width' => '600',
                                'type' => 'default',
                                'size' => '',
                                'shape' => '',
                                'icon' => ''
                            ]
                        ],
                        'columns' => [
                            [
                            'title' => 'UID',
                            'key' => 'id',
                            'width' => '50px'
                            ],
                            [
                                'title' => '头像',
                                'key' => 'avatar',
                                'width' => '80px'
                            ],
                            [
                                'title' => '昵称',
                                'key' => 'nickname',
                                'width' => '150px'
                            ],
                            [
                                'title' => '用户名',
                                'key' => 'username',
                                '2idth' => '150px'
                            ],
                            [
                                'title' => '操作',
                                'key' => 'right_button_list',
                                'minWidth' => '50px',
                                'type' => 'template',
                                'template' => 'right_button_list'
                            ]
                        ]
                    ]
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
        if (request()->isPost()) {
            // 数据验证
            $post = input('post.');
            
            // 数据构造
            $data = [
                'nickname' => $post['nickname'],
                'username' => $post['username'],
                'password' => user_md5($post['password']), // 密码不能明文，需要加密存储。
                'avatar'   => isset($post['avatar']) ? $post['avatar'] : '',
                'status'   => 1,
                'register_time'   => time(),
            ];

            // 存储数据
            $ret = Db::name('core_user')->insert($data);
            if ($ret) {
                return json(
                    [
                        'code' => 200,
                        'msg' => '添加用户成功',
                        'data' => [
                        ]
                    ]
                );
            } else {
                return json(
                    [
                        'code' => 0,
                        'msg' => '添加用户失败',
                        'data' => [
                        ]
                    ]
                );
            }
        } else {
            return json(
                [
                    'code' => 200,
                    'msg' => '成功',
                    'data' => [
                        'form_data' => [
                            'form_items' => [
                                [
                                    'name' => 'nickname',
                                    'title' => '昵称',
                                    'type' => 'text',
                                    'placeholder' => '请输入昵称',
                                    'tip' => '昵称类似微信昵称可以重复不能用来登录系统'
                                ],
                                [
                                    'name' => 'username',
                                    'title' => '用户名',
                                    'type' => 'text',
                                    'placeholder' => '请输入用户名',
                                    'tip' => '用户名唯一不重复，可以用来登录系统'
                                ],
                                [
                                    'name' => 'password',
                                    'title' => '密码',
                                    'type' => 'text',
                                    'placeholder' => '请输入用户密码',
                                    'tip' => '密码必须要包含字母数字和符号中的两种'
                                ],
                                [
                                    'name' => 'avatar',
                                    'title' => '头像',
                                    'type' => 'image',
                                    'placeholder' => '请上传用户头像',
                                    'tip' => '用户头像'
                                ],
                                
                            ],
                            'form_values' => [
                                'nickname' => '',
                                'username' => '',
                                'password' => '',
                            ],
                            'form_rules' => [
                                'nickname' =>  [
                                    [
                                        'required' => true,
                                        'message' => '请填写昵称',
                                        'trigger' => 'change'
                                    ]
                                ],
                                'username' =>  [
                                    [
                                        'required' => true,
                                        'message' => '请填写用户名',
                                        'trigger' => 'change'
                                    ]
                                ],
                                'password' =>  [
                                    [
                                        'required' => true,
                                        'message' => '请填写密码',
                                        'trigger' => 'change'
                                    ]
                                ]
                            ]
                        ]
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
        if (request()->isPost()) {
            return json(
                [
                    'code' => 200,
                    'msg' => '成功',
                    'data' => [
                    ]
                ]
            );
        } else {
            return json(
                [
                    'code' => 200,
                    'msg' => '成功',
                    'data' => [
                        'form_data' => [
                            'form_items' => [
                                [
                                    'name' => 'pid',
                                    'title' => '上级',
                                    'type' => 'select',
                                    'options' =>  [
                                        [
                                            'title' => '测试',
                                            'value' => ''
                                        ]
                                    ],
                                    'placeholder' => '请选择上级',
                                    'tip' => '选择上级后会限制权限范围不大于上级'
                                ],
                                [
                                    'name' => 'name',
                                    'title' => '英文名',
                                    'type' => 'text',
                                    'placeholder' => '请输入英文名',
                                    'tip' => '英文名其实可以理解为一个系统代号'
                                ],
                                [
                                    'name' => 'title',
                                    'title' => '角色名称',
                                    'type' => 'text',
                                    'placeholder' => '请输入角色名称',
                                    'tip' => '角色名称也可以理解为部门名称'
                                ]
                            ],
                            'form_values' => [
                                'pid' => 0,
                                'name' => '',
                                'title' => '',
                            ],
                            'form_rules' => [
                                'name' =>  [
                                    [
                                        'required' => true,
                                        'message' => '请填写角色英文名称',
                                        'trigger' => 'change'
                                    ]
                                ],
                                'title' =>  [
                                    [
                                        'required' => true,
                                        'message' => '请填写角色名称',
                                        'trigger' => 'change'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            );
        } 
    }

    /**
     * 删除
     * 
     * @return \think\Response
     */
    public function delete($id)
    {
        $ret = Db::name('core_user')
            ->where(['id' => $id])
            ->useSoftDelete('delete_time', time())
            ->delete();
        if ($ret) {
            return json(
                [
                    'code' => 200,
                    'msg' => '删除成功',
                    'data' => []
                ]
            );
        } else {
            return json(
                [
                    'code' => 200,
                    'msg' => '删除错误',
                    'data' => []
                ]
            );
        }
    }
}
