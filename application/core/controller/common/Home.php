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

use think\Request;
use app\core\controller\common\Common;

/**
 * 前台公共继承控制器
 *
 * @author jry <ijry@qq.com>
 */
class Home extends Common
{
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
