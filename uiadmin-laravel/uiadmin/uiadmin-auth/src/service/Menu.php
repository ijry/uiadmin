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

namespace uiadmin\auth\service;

use Illuminate\Support\Facades\Request;
use uiadmin\auth\model\Menu as MenuModel;
use uiadmin\auth\model\Role as RoleModel;

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
        if (is_string($userRoles)) {
            $userRoles = explode(',', $userRoles);
        }
        $adminAuthList = RoleModel::whereIn('name', $userRoles)
            ->select(['policys'])
            ->get();
        $adminAuth = [];
        foreach ($adminAuthList as $k => $v) {
            $v = explode(',', $v);
            $adminAuth = $adminAuth + $v;
        }
        $adminAuth = array_unique($adminAuth);

        // 获取列表
        $dataList = MenuModel::where('menu_layer', '=', 'admin')
            ->orderBy('sortnum')
            ->orderBy('id')
            ->get()->toArray();
        //var_dump($dataList);
        // 下面的处理存粹是为了后台界面显示的
        foreach ($dataList as $key => &$val) {
            if (!in_array('super_admin', $userRoles) && !in_array('/' . $val['apiPrefix'] . '/' . $val['menu_layer'] . $val['path'], $adminAuth)) {
                unset($dataList[$key]);
                continue;
            }
            if ($userRoles == ['super_admin'] && \uiadmin\core\util\Str::contains($val['path'], '.')) {
                unset($dataList[$key]);
                continue;
            }
        }
        $tree = new \uiadmin\core\util\Tree();
        $menuTree = $tree->list2tree($dataList, 'path', 'pmenu', 'children', 0, false);

        // 获取站点信息
        $siteInfo = config('uiadmin.site');
        $menuTree[0] = array_merge($menuTree[0], $siteInfo);

        return $menuTree; 
    }
}
