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
use demo\blog\model\Post as PostModel;

/**
 * 文章控制器
 *
 * @author jry <ijry@qq.com>
 */
class Post extends BaseHome
{
    /**
     * 获取站点信息
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
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
