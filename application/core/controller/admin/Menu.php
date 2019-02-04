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

use think\facade\Request;
use app\core\controller\common\Admin;

class Menu extends Admin
{
    /**
     * 后台左侧导航列表
     *
     * @return \think\Response
     */
    public function lists()
    {
        $_module_conf_list = cache('_module_conf_list');
        $menu_list = [];
        foreach ($_module_conf_list as $key => $val) {
            $menu_list = array_merge($menu_list, $val['admin_menu']);
        }
        //dump($menu_list);
        return json(['code' => 200, 'msg' => '成功', 'data' => ['menu_list' => $menu_list]]);
    }
}
