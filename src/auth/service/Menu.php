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
    public function getByUser($userRoles)
    {
        $adminAuthList = Db::name('auth_role')::where('name', 'in', $userRoles)
            ->column('adminAuth');
        $adminAuth = [];
        foreach ($adminAuthList as $k => $v) {
            $v = explode(',', $v);
            $adminAuth = $adminAuth + $v;
        }
        $adminAuth = array_unique($adminAuth);

        // 获取列表
        $dataList = Db::name('auth_menu')
            ->where('menuLayer', '=', 'admin')
            ->where('menuType', 'in', '-1,0,1,2') // 排除掉3纯接口
            ->order('sortnum asc,id asc')
            ->select();
        // 下面的处理存粹是为了后台界面显示的
        foreach ($dataList as $key => &$val) {
            if (!in_array('super_admin', $userRoles) && !in_array('/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . $val['path'], $adminAuth)) {
                unset($dataList[$key]);
                continue;
            }
            if ($userRoles == ['super_admin'] && \app\core\util\Str::contains($val['path'], '.')) {
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
