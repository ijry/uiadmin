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

namespace demo\blog\controller;

use think\Request;
use think\facade\View;
use uiadmin\core\controller\BaseHome;
use uiadmin\core\attributes\MenuItem;
use demo\blog\model\Post as PostModel;

/**
 * 文章控制器
 *
 * @author jry <ijry@qq.com>
 */
class Post extends BaseHome
{
    /**
     * 获取信息
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    // 下面的注解定义了一个http://127.0.0.1/api/v1/blog/post/info/:id接口
    #[MenuItem(["title" => "文章详情", "path" => "/blog/post/info", "pmenu" => "",
        "menuType" => 1, "menuLayer" => "home", "routeType" => "info",
        "apiSuffix" => "/:id", "apiParams" => "", "apiMethod" => "GET", "sortnum" => 0])]
    public function info($id)
    {
        // 文章信息
        $info = PostModel::where('id', $id)
            ->find();

        // 返回数据
        return json([
            'code' => 200,
            'msg'  => '成功',
            'data' => [
                'info' => $info
            ]
        ]);
    }
}
