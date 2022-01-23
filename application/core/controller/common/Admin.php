<?php
/**
 * +----------------------------------------------------------------------
 * | UiAdmin [ 渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <ijry@qq.com>
 * +----------------------------------------------------------------------
*/

namespace app\core\controller\common;

use think\facade\Request;
use think\Db;
use app\core\controller\common\Common;

/**
 * 后台公共继承控制器
 *
 * @author jry <ijry@qq.com>
 */
class Admin extends Common
{
    protected $login,$cloudAlias = 0,$cloudId = 0;

    protected function initialize()
    {
        // initialize
        parent::initialize();

        // 不需要验证登录的页面
        if (in_array(request()->path(), ['admin/core/user/login', 'api/v1/admin/core/user/login'])) {
            return true;
        }

        // 登录验证
        $ret = parent::isLogin();
        if ($ret['code'] != 200) {
            echo json_encode($ret);
            exit;
        }

        // 权限验证
        $uid = $ret['data']['data']->uid;
        if (!$uid) {
            return json(['code' => 0, 'msg' => '缺少UID', 'data' => []]);
        }
        // 获取该uid的角色id
        $role_list = Db::name('core_user')->where('id', $uid)->value('roles');
        if ($role_list) {
            // 超级管理员无需验证
            if (false !== strpos($role_list, 'super_admin')) {
                return true;
            }

            // 无需验证权限的接口，防止权限管理时未勾选导致菜单不显示
            if (in_array(request()->path(),
                ['api/v1/admin/core/index/cleanRuntime',
                'api/v1/admin/core/index/index',
                'api/v1/admin/core/menu/trees',
                'api/v1/admin/core/menu/lists',
                'api/v1/admin/core/user/login'])) {
                return true;
            }

            // 常规权限验证
            $adminAuth_list = Db::name('core_role')
                ->where('status', 1)
                ->where('name', 'in', $role_list)
                ->column('adminAuth');
            // 合并权限列表
            $adminAuth_list = explode(',', implode(',', $adminAuth_list));
            // 获取当前pathinfo
            $path_api = ltrim(Request::path(), 'api');
            $path_api = explode('/', $path_api);
            // dump($path_api);
            foreach ($adminAuth_list as $val) {
                $val = explode('/', $val);
                //dump($val);
                if ($val[1] == $path_api[1]
                    && $val[2] == $path_api[2]
                    && $val[3] == $path_api[3]
                    && $val[4] == $path_api[4]
                    && $val[5] == $path_api[5]) {
                    return true;
                }
            }
            return json(['code' => 0, 'msg' => '无此接口权限', 'data' => []]);
        } else {
            return json(['code' => 0, 'msg' => '无后台权限', 'data' => []]);
        }
    }

    /**
     * 是否登录
     *
     * @author jry <ijry@qq.com>
     */
    protected function isLogin($redirect = 0) {
        // 登录验证
        $ret = parent::isLogin();
        if ($ret['code'] != 200) {
            echo json_encode($ret);
            exit;
        } else {
            return (Array)$ret['data']['data'];
        }
    }
}
