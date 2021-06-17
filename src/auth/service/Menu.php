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

namespace uniadmin\auth\service;

use think\Request;
use think\facade\Db;

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
        // 获取用户角色
        $roles = explode(',' , Db::name('auth_user')->where('id', $uid)
            ->value('roles'));
        $adminAuthList = Db::name('auth_role')::where('name', 'in', $userRoles)
            ->column('admin_auth');
        $adminAuth = [];
        foreach ($adminAuthList as $k => $v) {
            $v = explode(',', $v);
            $adminAuth = $adminAuth + $v;
        }
        $adminAuth = array_unique($adminAuth);

        // 获取列表
        $dataList = $this->core_menu
            ->where('menu_layer', '=', 'admin')
            ->where('menu_type', 'in', '-1,0,1,2') // 排除掉3纯接口
            ->order('sortnum asc,id asc')
            ->select()->toArray();
        // 下面的处理存粹是为了后台界面显示的
        foreach ($dataList as $key => &$val) {
            if (!in_array('super_admin', $roles) && !in_array('/' . $val['api_prefix'] . '/' . $val['menu_layer'] . $val['path'], $adminAuth)) {
                unset($dataList[$key]);
                continue;
            }
            if ($roles == ['super_admin'] && \app\core\util\Str::contains($val['path'], '.')) {
                unset($dataList[$key]);
                continue;
            }
        }
        $tree = new \uniadmin\core\util\Tree();
        $menuTree = $tree->list2tree($dataList, 'path', 'pmenu', 'children', 0, false);

        // 获取站点信息
        $siteInfo = config('uniadmin.site');
        $menuTree[0] = array_merge($menuTree[0], $siteInfo);

        return $menuTree; 
    }
}
