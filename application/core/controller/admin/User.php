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
use think\Validate;
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
        return json([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'data_list' => $data_list,
                    'dynamic_data' => [
                        'top_button_list' => [
                            'add' => [
                                'page_type' => 'modal',
                                'modal_data' => [
                                    'title' => '添加用户',
                                    'api' => 'v1/core/admin/user/add',
                                    'width' => '600',
                                ],
                                'route' => '',
                                'title' => '添加用户',
                                'type' => 'default',
                                'size' => '',
                                'shape' => '',
                                'icon' => ''
                            ]
                        ],
                        'right_button_list' => [
                            'edit' => [
                                'page_type' => 'modal',
                                'modal_data' => [
                                    'title' => '修改用户信息',
                                    'api' => 'v1/core/admin/user/edit',
                                    'width' => '600',
                                ],
                                'route' => '',
                                'title' => '修改',
                                'type' => 'default',
                                'size' => '',
                                'shape' => '',
                                'icon' => ''
                            ],
                            'delete' => [
                                'page_type' => 'modal',
                                'modal_data' => [
                                    'type' => 'confirm',
                                    'title' => '确认要删除该用户吗？',
                                    'api' => 'v1/core/admin/user/delete',
                                    'width' => '600',
                                    'okText' => '确认删除',
                                    'cancelText' => '取消操作',
                                    'content' => '<p><p>删除后将清空绑定的所有登录验证记录</p></p>',
                                ],
                                'route' => '',
                                'title' => '删除',
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
            $validate = Validate::make(
                [
                    'nickname'  => 'require',
                    'username' => 'require',
                    'password' => 'require'
                ],
                [
                    'nickname.require' => '昵称必须',
                    'username.require' => '用户名必须',
                    'password.require' => '密码必须'
                ]
            );
            $data = input('post.');
            if (!$validate->check($data)) {
                return json(['code' => 200, 'msg' => $validate->getError(), 'data' => []]);
            }
            
            // 数据构造
            $data_db = [];
            $data_db['nickname'] = $data['nickname'];
            $data_db['username'] = $data['username'];
            $data_db['password'] = user_md5($data['password']); // 密码不能明文需要加密存储
            $data_db['avatar']   = isset($data['avatar']) ? $data['avatar'] : '';
            $data_db['status']   = 1;
            $data_db['register_time']   = time();

            // 存储数据
            $ret = Db::name('core_user')->insert($data_db);
            if ($ret) {
                return json(['code' => 200, 'msg' => '添加用户成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '添加用户失败', 'data' => []]);
            }
        } else {
            return json([
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
                            ]
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
        if (request()->isPost()) {
            // 数据验证
            $validate = Validate::make([
                    'nickname'  => 'require',
                    'username' => 'require',
                ],
                [
                    'nickname.require' => '昵称必须',
                    'username.require' => '用户名必须',
                ]);
            $data = input('post.');
            if (!$validate->check($data)) {
                return json(['code' => 200, 'msg' => $validate->getError(), 'data' => []]);
            }

            // 数据构造
            $data_db = [];
            if ($data['nickname']) {
                $data_db['nickname'] = $data['nickname'];
            }
            if ($data['username']) {
                $data_db['username'] = $data['username'];
            }
            if ($data['avatar']) {
                $data_db['avatar'] = $data['avatar'];
            }
            if ($data['password']) {
                $data_db['password'] = user_md5($data['password']); // 密码不能明文需要加密存储
            }
            if (count($data_db) <= 0 ) {
                return json(['code' => 0, 'msg' => '无数据修改提交', 'data' => []]);
            }

            // 存储数据
            $ret = Db::name('core_user')
                ->where('id', $id)
                ->update($data_db);
            if ($ret) {
                return json(['code' => 200, 'msg' => '添加用户成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '添加用户失败', 'data' => []]);
            } 
        } else {
            $info = Db::name('core_user')
                ->where('id', $id)
                ->find();
            return json([
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
                                'placeholder' => '不填则不修改密码',
                                'tip' => '密码必须要包含字母数字和符号中的两种'
                            ]
                            
                        ],
                        'form_values' => [
                            'nickname' => $info['nickname'],
                            'username' => $info['username'],
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
        $ret = Db::name('core_user')
            ->where(['id' => $id])
            ->useSoftDelete('delete_time', time())
            ->delete();
        if ($ret) {
            return json(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return json(['code' => 200, 'msg' => '删除错误', 'data' => []]);
        }
    }
}
