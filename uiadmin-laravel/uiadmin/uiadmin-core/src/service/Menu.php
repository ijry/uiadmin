<?php
/**
 * +----------------------------------------------------------------------
 * | uiadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/

namespace uiadmin\core\service;

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
    public function getByUser($userRoles)
    {
        // todo根据用户角色获取菜单
        $menuListJson = file_get_contents(config_path() . 'menu.json');
        $menuList = json_decode($menuListJson, true);
        return $menuList['menu']; 
    }
}
