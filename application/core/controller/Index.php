<?php
// +----------------------------------------------------------------------
// | tpvue [ 模块化渐进式后台 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2019 http://tpvue.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace tpvue\core\controller;

use think\Controller;
use think\Request;

class Index extends Controller
{
    /**
     * 首页
     *
     * @return \think\Response
     */
    public function index()
    {
        return 'tpvue后台接口运行中...<br/>接口域名：' . request()->domain() . '<br/>';
    }
}
