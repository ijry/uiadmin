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
        $data_list = db('core_auth_role')
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
                    'form_api' => [
                        'add' => 'v1/core/admin/auth_role/add',
                        'edit' => 'v1/core/admin/auth_role/edit',
                        'delete' => 'v1/core/admin/auth_role/delete'
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
     * 修改
     *
     * @return \think\Response
     */
    public function edit($id)
    {
        if(request()->isPost()){
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
        $ret = db('core_auth_role')
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
