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
 * 模块
 */
class Module extends Admin
{
    private $core_module;

    protected function initialize()
    {
        parent::initialize();
        $this->core_module = Db::name('core_module');
    }

    /**
     * 模块列表
     *
     * @return \think\Response
     */
    public function lists()
    {
        //用户列表
        $data_list = $this->core_module
            ->select();
        $tree      = new Tree();
        $data_list = $tree->list2tree($data_list);

        //构造动态页面数据
        $ia_dylist      = new \app\core\util\iadypage\IaDylist();
        $list_data = $ia_dylist->init()
            ->addTopButton('add', '创建新模块', ['api' => '/v1/admin/core/module/add'])
            ->addRightButton('config', '设置', ['api' => '/v1/admin/core/module/config', 'title' => '配置'])
            ->addRightButton('export', '导出', ['api' => '/v1/admin/core/module/export', 'title' => '导出模块信息'])
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/core/module/edit', 'title' => '修改模块信息'])
            ->addColumn('id' , 'ID', ['width' => '50px'])
            ->addColumn('name', '名称', ['width' => '120px'])
            ->addColumn('title', '标题', ['width' => '120px'])
            ->addColumn('description', '描述', ['width' => '240px'])
            ->addColumn('developer', '开发者', ['width' => '100px'])
            ->addColumn('version', '版本', ['width' => '80px'])
            ->addColumn('build', 'Build', ['width' => '150px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('status', '状态', ['width' => '50px'])
            ->addColumn('right_button_list', '操作', [
                'minWidth' => '50px',
                'type' => 'template',
                'template' => 'right_button_list'
            ])
            ->getData();
        
        //返回数据
        return json([
            'code' => 200, 'msg' => '成功', 'data' => [
                'data_list' => $data_list,
                'list_data' => $list_data
            ]
        ]);
    }
}
