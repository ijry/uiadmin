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
 * 配置
 */
class Config extends Admin
{
    private $core_module;

    protected function initialize()
    {
        parent::initialize();
        $this->core_config = Db::name('core_config');
    }

    /**
     * 模块列表
     *
     * @return \think\Response
     */
    public function lists()
    {
        //用户列表
        $data_list = $this->core_config
            ->select();

        //构造动态页面数据
        $ia_dylist      = new \app\core\util\iadypage\IaDylist();
        $list_data = $ia_dylist->init()
            ->addTopButton('add', '添加', ['api' => '/v1/admin/core/config/add'])
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/core/config/edit', 'title' => '修改配置信息'])
            ->addColumn('id' , 'ID', ['width' => '50px'])
            ->addColumn('name', '名称', ['width' => '100px'])
            ->addColumn('title', '标题', ['width' => '100px'])
            ->addColumn('config_cate', '分组', ['width' => '80px'])
            ->addColumn('config_type', '配置类型', ['width' => '80px'])
            ->addColumn('placeholder', 'placeholder', ['width' => '150px'])
            ->addColumn('tip', '说明', ['width' => '200px'])
            ->addColumn('is_system', '系统', ['width' => '50px'])
            ->addColumn('is_dev', '开发者', ['width' => '80px'])
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
