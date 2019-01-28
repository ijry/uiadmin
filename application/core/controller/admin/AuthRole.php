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
 * 角色
 */
class AuthRole extends Admin
{
    /**
     * 角色列表
     *
     * @return \think\Response
     */
    public function lists()
    {
        $data_list = Db:name('core_auth_role')
            ->where(['delete_time' => 0])
            ->select();
        $tree      = new Tree();
        $data_list = $tree->list2tree($data_list);
        //dump($data_list);
        return json(
            [
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'data_list' => $data_list,
                    'dynamic_data' => [
                        'top_button_list' => [
                            'add' => [
                                'page_type' => 'modal',
                                'modal_data' => [
                                    'title' => '添加角色',
                                    'api' => 'v1/core/admin/auth_role/add',
                                    'width' => '600',
                                ],
                                'route' => '',
                                'title' => '添加角色',
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
                                    'title' => '修改角色',
                                    'api' => 'v1/core/admin/auth_role/edit',
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
                                    'title' => '确认要删除该角色吗？',
                                    'api' => 'v1/core/admin/auth_role/delete',
                                    'width' => '600',
                                    'okText' => '确认删除',
                                    'cancelText' => '取消操作',
                                    'content' => '<p><p>如果该角色下有子角色需要先删除或者移动</p><p>如果该角色下有成员需要先移除才可以删除</p><p>删除该角色将会删除对应的权限数据</p></p>',
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
                            'title' => 'ID',
                            'key' => 'id',
                            'width' => '40px'
                            ],
                            [
                                'title' => '部门',
                                'key' => 'title',
                                'minWidth' => '50px'
                            ],
                            [
                                'title' => '排序',
                                'key' => 'sortnum'
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
        if(request()->isPost()){
            return json(['code' => 200, 'msg' => '成功', 'data' => []]);
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
     * 修改
     *
     * @return \think\Response
     */
    public function edit($id)
    {
        if(request()->isPost()){
            return json(['code' => 200,'msg' => '成功','data' => []]);
        } else {
            return json([
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
        $ret = Db:name('core_auth_role')
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
