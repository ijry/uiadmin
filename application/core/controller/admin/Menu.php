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
        $dynamic_data = $ia_dylist->init()
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
            ->addColumn('title', '菜单标题', ['width' => '150px'])
            ->addColumn('menu_type', '类型', ['width' => '50px'])
            ->addColumn('api_method', '请求方法', ['width' => '100px'])
            ->addColumn('admin_api', '后台接口', ['minWidth' => '150px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('admin_api', '后台接口', ['minWidth' => '150px'])
            ->addColumn('right_button_list', '操作', [
                'minWidth' => '50px',
                'type' => 'template',
                'template' => 'right_button_list'
            ])
            ->getData();

        //返回数据
        return json(['code' => 200, 'msg' => '成功', 'data' => [
            'data_list' => $menu_tree,
            'dynamic_data' => $dynamic_data
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
            $data_db = [];
            $data_db['pid'] = isset($data['pid']) ? $data['pid'] : '';
            $data_db['name'] = $data['name'];
            $data_db['title'] = $data['title'];
            $data_db['sortnum'] = isset($data['sortnum']) ? $data['sortnum'] : 0;
            $data_db['admin_auth'] = isset($data['admin_auth']) ? implode(',', $data['admin_auth']) : ''; //后台权限
            $data_db['api_auth'] = isset($data['api_auth']) ? implode(',', $data['api_auth']) : ''; //接口权限
            $data_db['status'] = 1;
            
            //存储数据
            $ret = $this->core_role->insert($data_db);
            if ($ret) {
                return json(['code' => 200, 'msg' => '添加角色成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '添加角色失败', 'data' => []]);
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
                ->order('sortnum asc')
                ->select();
            $tree      = new Tree();
            $menu_tree = $tree->array2tree($menu_list, 'title', 'path', 'pmenu', 0, false);
            $menu_tree_select = [];
            foreach ($menu_tree as $key => $val) {
                $menu_tree_select[$key]['title'] = $val['title_show'];
                $menu_tree_select[$key]['value'] = $val['path'];
            }
            return json(
                [
                    'code' => 200,
                    'msg' => '成功',
                    'data' => [
                        'form_data' => [
                            'form_method' => 'post',
                            'form_items' => [
                                [
                                    'name' => 'module',
                                    'title' => '模块',
                                    'type' => 'select',
                                    'options' => $module_list_select,
                                    'placeholder' => '请选择模块',
                                    'tip' => '模块是一个可分享使用的最小功能包'
                                ],
                                [
                                    'name' => 'pmenu',
                                    'title' => '上级菜单',
                                    'type' => 'select',
                                    'options' => $menu_tree_select,
                                    'placeholder' => '请选择上级菜单',
                                    'tip' => '上级菜单'
                                ],
                                [
                                    'name' => 'title',
                                    'title' => '菜单标题',
                                    'type' => 'text',
                                    'placeholder' => '请输入菜单标题',
                                    'tip' => '菜单标题是显示在左侧列表中的'
                                ],
                                [
                                    'name' => 'tip',
                                    'title' => '菜单说明',
                                    'type' => 'text',
                                    'placeholder' => '请输入菜单说明',
                                    'tip' => '好的说明有助于用户理解'
                                ],
                                [
                                    'name' => 'menu_type',
                                    'title' => '菜单类型',
                                    'type' => 'radio',
                                    'options' => [
                                        [
                                            'title' => '分组',
                                            'value' => 0,
                                        ],
                                        [
                                            'title' => '功能页面+接口',
                                            'value' => 1,
                                        ],
                                        [
                                            'title' => '功能按钮+接口',
                                            'value' => 2,
                                        ],
                                        [
                                            'title' => '纯接口',
                                            'value' => 3,
                                        ]
                                    ],
                                    'placeholder' => '请选择菜单类型',
                                    'tip' => '菜单类型'
                                ],
                                [
                                    'name' => 'path',
                                    'title' => '接口路径',
                                    'type' => 'text',
                                    'placeholder' => '请输入接口路径',
                                    'tip' => '接口路径举例：/core/user/lists'
                                ],
                                [
                                    'name' => 'api_prefix',
                                    'title' => '接口前缀',
                                    'type' => 'text',
                                    'placeholder' => '接口前缀',
                                    'tip' => '一般默认v1'
                                ],
                                [
                                    'name' => 'api_suffix',
                                    'title' => '接口参数',
                                    'type' => 'text',
                                    'placeholder' => '请输入接口参数',
                                    'tip' => '接口参数举例：/:id/:name'
                                ],
                                [
                                    'name' => 'api_method',
                                    'title' => '请求方法',
                                    'type' => 'checkbox',
                                    'options' => [
                                        [
                                            'title' => 'GET',
                                            'value' => 'GET',
                                        ],
                                        [
                                            'title' => 'POST',
                                            'value' => 'POST',
                                        ],
                                        [
                                            'title' => 'PUT',
                                            'value' => 'PUT',
                                        ],
                                        [
                                            'title' => 'DELETE',
                                            'value' => 'DELETE',
                                        ]
                                    ],
                                    'placeholder' => '请输入请求方法',
                                    'tip' => '尽量符合Restful风格'
                                ],
                                [
                                    'name' => 'is_iadypage',
                                    'title' => '动态页面',
                                    'type' => 'radio',
                                    'options' => [
                                        [
                                            'title' => '是',
                                            'value' => 1,
                                        ],
                                        [
                                            'title' => '否',
                                            'value' => 0,
                                        ]
                                    ],
                                    'placeholder' => '请选择是否自动生成页面',
                                    'tip' => '系统内容了动态页面技术，可以自动生成后台前端页面'
                                ],
                                [
                                    'name' => 'is_hide',
                                    'title' => '是否隐藏',
                                    'type' => 'radio',
                                    'options' => [
                                        [
                                            'title' => '是',
                                            'value' => 1,
                                        ],
                                        [
                                            'title' => '否',
                                            'value' => 0,
                                        ]
                                    ],
                                    'placeholder' => '请选择是否隐藏',
                                    'tip' => '有时候一些功能不需要可以隐藏'
                                ],
                            ],
                            'form_values' => [
                                'module' => '',
                                'pmenu' => '',
                                'path' => '',
                                'api_prefix' => 'v1',
                                'api_suffix' => '',
                                'api_method' => '',
                                'title' => '',
                                'tip' => '',
                                'menu_type' => '',
                                'is_iadypage' => 1,
                                'is_hide' => 0
                            ],
                            'form_rules' => [
                                'title' =>  [
                                    [
                                        'required' => true,
                                        'message' => '请填写菜单标题',
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
            if (isset($data_db['admin_auth']) && is_array($data_db['admin_auth'])) {
                $data_db['admin_auth'] = implode(',', $data_db['admin_auth']);
            }
            if (isset($data_db['api_auth']) && is_array($data_db['api_auth'])) {
                $data_db['api_auth'] = implode(',', $data_db['api_auth']);
            }
            if (count($data_db) <= 0 ) {
                return json(['code' => 0, 'msg' => '无数据修改提交', 'data' => []]);
            }

            // 存储数据
            $ret = $this->core_role
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

            return json([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'form_data' => [
                        'form_method' => 'put',
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
                            ],
                            [
                                'name' => 'admin_auth',
                                'title' => '后台权限',
                                'type' => 'checkboxtree',
                                'options' => [
                                    'columns' => [
                                        [
                                            'title' => '菜单(接口)',
                                            'key' => 'title',
                                            'minWidth' => '150px'
                                        ],
                                        [
                                            'title' => '说明',
                                            'key' => 'tip'
                                        ],
                                        [
                                            'title' => '接口',
                                            'key' => 'admin_auth'
                                        ],
                                        [
                                            'title' => '类型',
                                            'key' => 'menu_type',
                                            'width' => '40px'
                                        ]
                                    ],
                                    'data' => $menu_tree
                                ],
                                'extra' => [
                                    'expand-key' => 'title'
                                ],
                                'placeholder' => '请勾选该角色的权限',
                                'tip' => ''
                            ]
                        ],
                        'form_values' => [
                            'pid' => $info['pid'],
                            'name' => $info['name'],
                            'title' => $info['title'],
                            'admin_auth' => $info['admin_auth'],
                            'api_auth' => $info['api_auth'],
                            'sortnum' => $info['sortnum'],
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
     * 后台左侧导航列表路由规则
     *
     * @return \think\Response
     */
    public function lists()
    {
        // 计算路由
        $data_list = $this->core_menu
            ->where('menu_type', '>', 0)
            ->select();
        foreach ($data_list as $key => &$val) {
            $val['api'] = $val['api_prefix'] . '/admin' . $val['path'];
        }
        return json(['code' => 200, 'msg' => '成功', 'data' => ['data_list' => $data_list]]);
    }
}
