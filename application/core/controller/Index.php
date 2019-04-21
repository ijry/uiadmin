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

namespace app\core\controller;

use think\Db;
use think\Request;
use think\Controller;

/**
 * 默认控制器
 *
 * @author jry <ijry@qq.com>
 */
class Index extends Controller
{
    /**
     * 首页
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function index()
    {
        //dump(user_md5('initadmin.net', 'initadmin-auth-key'));

        // 环境检测
        if (!function_exists('mb_strlen')) {
            dump('缺少php-mbstring扩展');
        }
        return 'InitAdmin后台接口actionphp版本运行中...<br/>'
            .'接口域名：' . request()->domain() . '/api/<br/>'
            .'后台地址：<a href="https://admin.jiangruyi.com/#/home?api='
            .request()->domain().'/api/" target="_blank">点击登录后台管理</a>';
    }
}
