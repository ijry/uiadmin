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

namespace uiadmin\core\admin;

use uiadmin\core\BaseController;

/**
 * 基础控制器
 *
 * @author jry <ijry@qq.com>
 */
class BaseAdmin extends BaseController
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
