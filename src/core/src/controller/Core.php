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
 * 核心控制器
 *
 * @author jry <ijry@qq.com>
 */
class Core
{
    /**
     * 获取站点信息
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function info()
    {
        // 返回数据
        return json_encode([
            'code' => 200,
            'msg'  => '成功',
            'data' => [
                'siteInfo' => [
                    'title' => config("uniadmin.title"),
                    'logo' => config("uniadmin.logo"),
                    'logoTitle' => config("uniadmin.logoTitle")
                ]
            ]
        ]);
    }
}
