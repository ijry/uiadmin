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

namespace tpvue\core\controller;

use think\Controller;
use think\Request;

class Menu extends Controller
{
    /**
     * 后台左侧导航列表
     *
     * @return \think\Response
     */
    public function lists()
    {
        $_module_conf_list = cache('_module_conf_list');
        $menu_list = $_module_conf_list['core']['muenu_cate_top'];
        foreach ($_module_conf_list as $key => $val) {
            $menu_list = array_merge($menu_list, $val['admin_menu']);
        }
        //dump($menu_list);
        return json(['code' => 200, 'msg' => '成功', 'data' => ['menu_list' => $menu_list]]);
    }
}
