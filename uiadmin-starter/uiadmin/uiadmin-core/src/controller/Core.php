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

namespace uiadmin\core\controller;

use think\facade\View;

/**
 * 核心控制器
 *
 * @author jry <ijry@qq.com>
 */
class Core extends BaseHome
{
    public function index()
    {
        // 返回数据
        View::config(['view_path' => __DIR__ . '/../view/core/']);
        return View::fetch('index');
    }

    /**
     * 获取站点信息
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function info()
    {
        // 返回数据
        return json([
            'code' => 200,
            'msg'  => '成功',
            'data' => [
                'siteInfo' => [
                    'title' => config("uiadmin.site.title"),
                    'logo' => config("uiadmin.site.logo"),
                    'logoTitle' => config("uiadmin.site.logoTitle")
                ]
            ]
        ]);
    }
}
