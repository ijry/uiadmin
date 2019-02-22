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

    protected function initialize()
    {
        parent::initialize();
        $this->core_menu = Db::name('core_menu');
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
        return json(['code' => 200, 'msg' => '成功', 'data' => [
            'data_list' => $menu_tree,
            'dynamic_data' => [
                'top_button_list' => [
                    'add' => [
                        'page_type' => 'modal',
                        'modal_data' => [
                            'title' => '添加菜单',
                            'api' => '/v1/admin/core/menu/add',
                            'width' => '600',
                        ],
                        'route' => '',
                        'title' => '添加菜单',
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
                            'title' => '修改菜单',
                            'api' => '/v1/admin/core/menu/edit',
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
                            'title' => '确认要删除该菜单吗？',
                            'api' => '/v1/admin/core/menu/delete',
                            'width' => '600',
                            'okText' => '确认删除',
                            'cancelText' => '取消操作',
                            'content' => '<p>删除菜单不可恢复</p></p>',
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
                        'title' => '所属模块',
                        'key' => 'module',
                        'width' => '80px'
                    ],
                    [
                        'title' => '菜单标题',
                        'key' => 'title',
                        'minWidth' => '150px'
                    ],
                    [
                        'title' => '类型',
                        'key' => 'menu_type',
                        'width' => '50px'
                    ],
                    [
                        'title' => '请求方法',
                        'key' => 'api_method',
                        'width' => '100px'
                    ],
                    [
                        'title' => '后台接口',
                        'key' => 'admin_api',
                        'minWidth' => '150px'
                    ],
                    [
                        'title' => '排序',
                        'key' => 'sortnum',
                        'width' => '50px'
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
        ]]);
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
