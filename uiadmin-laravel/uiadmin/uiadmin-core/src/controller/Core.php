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

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\View\FileViewFinder;

/**
 * 核心控制器
 *
 * @author jry <ijry@qq.com>
 */
class Core extends BaseHome
{
    public function __construct()
    {
        // 方法一（该方法框架默认的分页模版会找不到）
        // public_path为获取public文件夹的绝对路径
        $path = [dirname(dirname(__FILE__))  . '/view'];
        // View::setFinder设置视图获取路径
        View::setFinder(new FileViewFinder(App::make('files'), $path));
    }

    public function index()
    {
        // 返回数据
        return view('core.index', [
        ]);
    }

    /**
     * 获取站点信息
     *
     * @return \Response
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
