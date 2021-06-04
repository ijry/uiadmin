<?php
/**
 * +----------------------------------------------------------------------
 * | uniadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2021 http://uniadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/

namespace uniadmin\core\controller;

use think\Request;

/**
 * 菜单服务
 *
 * @author jry <ijry@qq.com>
 */
class Menu
{
    /**
     * 获取菜单
     *

     * @author jry <ijry@qq.com>
     */
    public function getByUser($uid, $userRoles)
    {
        // todo根据用户角色获取菜单
        $menuListJson = file_get_contents(config_path() . 'menu.json');
        return json_decode($menuListJson, true); 
    }
}
