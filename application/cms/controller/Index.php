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

namespace app\cms\controller;

use think\Db;
use think\Request;
use think\Validate;
use app\core\controller\common\Home;

/**
 * 默认控制器
 *
 * @author jry <ijry@qq.com>
 */
class Index extends Home
{
    private $cms_post,$cms_cate;

    public function initialize()
    {
        parent::initialize();
        $this->cms_post = new \app\cms\model\Post();
        $this->cms_cate = new \app\cms\model\Cate();
    }

    /**
     * 首页
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function index()
    {
        $dataList = $this->cms_post
            ->where('status', '=', 1)
            ->where('reviewStatus', '=', 1)
            ->select()
            ->toArray();
        return $this->return(['code' => 200, 'msg' => '成功', 'data' => [
            'dataList' =>$dataList
        ]]);
    }
}
