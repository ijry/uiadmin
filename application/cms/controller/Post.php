<?php
/**
 * +----------------------------------------------------------------------
 * | UniAdmin [ 渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2020 http://uniadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <ijry@qq.com>
 * +----------------------------------------------------------------------
*/

namespace app\cms\controller;

use think\Db;
use think\Request;
use think\Validate;
use app\core\controller\common\Home;

/**
 * 文章控制器
 *
 * @author jry <ijry@qq.com>
 */
class Post extends Home
{
    private $cms_post,$cms_cate;

    public function initialize()
    {
        parent::initialize();
        $this->cms_post = new \app\cms\model\Post();
        $this->cms_cate = new \app\cms\model\Cate();
    }

    /**
     * 我的
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function my()
    {
        $login = $this->isLogin();
        $data_list = $this->cms_post
            ->where('uid', '=', $login['uid'])
            ->select()
            ->toArray();
        return $this->return(['code' => 200, 'msg' => '成功', 'data' => [
            'data_list' =>$data_list
        ]]);
    }

    /**
     * 列表
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function lists($cid)
    {
        // 分类信息
        $cate_info = $this->cms_cate
            ->where('id', '=', $cid)
            ->where('status', '=', 1)
            ->find()
            ->toArray();

        // 文章列表
        $data_list = $this->cms_post
            ->where('cid', '=', $cid)
            ->where('status', '=', 1)
            ->where('review_status', '=', 1)
            ->select()
            ->toArray();
        return $this->return(['code' => 200, 'msg' => '成功', 'data' => [
            'cate_info' => $cate_info,
            'data_list' =>$data_list
        ]]);
    }

    /**
     * 详情
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function info($id)
    {
        $info = $this->cms_post
            ->where('id', '=', $id)
            ->where('status', '=', 1)
            ->where('review_status', '=', 1)
            ->find()
            ->toArray();
        return $this->return(['code' => 200, 'msg' => '成功', 'data' => [
            'info' =>$info
        ]]);
    }
}
