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
    protected function initialize()
    {
        // initialize
        parent::initialize();

        // 登录验证
        $ret = $this->is_login();
        if ($ret['code'] != 200) {
            echo json_encode($ret);
            exit;
        }

        // 权限验证
        $uid = $ret['data']['data']->uid;
        if (!$uid) {
            echo json_encode(['code' => 0, 'msg' => '缺少UID', 'data' => []]);
            exit;
        }
        // 获取该uid的角色id
        $role_list = Db::name('core_user')->where('id', $uid)->value('roles');
        if ($role_list) {
            // 超级管理员无需验证
            if (false !== strpos($role_list, 'super_admin')) {
                return true;
            }

            // 常规权限验证
            $admin_auth_list = Db::name('core_role')
                ->where('status', 1)
                ->where('name', 'in', $role_list)
                ->column('admin_auth');
            // 合并权限列表
            $admin_auth_list = explode(',', implode(',', $admin_auth_list));
            // 获取当前pathinfo
            $path_api = ltrim(Request::path(), 'api');
            $path_api = explode('/', $path_api);
            // dump($path_api);
            foreach ($admin_auth_list as $val) {
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
            echo json_encode(['code' => 0, 'msg' => '无此接口权限', 'data' => []]);
            exit;
        } else {
            echo json_encode(['code' => 0, 'msg' => '无后台权限', 'data' => []]);
            exit;
        }
    }
}
