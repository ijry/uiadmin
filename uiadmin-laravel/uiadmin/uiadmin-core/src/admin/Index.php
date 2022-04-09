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

use uiadmin\core\admin\BaseAdmin;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

/**
 * 默认控制器
 *
 * @author jry <ijry@qq.com>
 */
class Index extends BaseAdmin
{

    /**
     * API对接
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function api()
    {
        // 获取接口信息
        $apiBase = scheme() . '://' . $_SERVER['HTTP_HOST']  . config("uiadmin.site.apiPrefix");

        // 返回
        return response()->json(['code' => 200, 'msg' => '成功', 'data' => [
            'lang' => 'php',
            'framework' => 'thinkphp6.0',
            'name' => "UiAdmin",
            'title' => config("uiadmin.site.title"),
            'stype' => '应用', // 菜单分组类型
            'version' => get_config("version"),
            'domainRoot' => scheme() . '://' . $_SERVER['HTTP_HOST'], // 主要给远程组件和iframe用
            'api' => [
                'apiBase' => $apiBase,
                'apiPrefix' => config("uiadmin.site.apiPrefix"),
                'apiLogin' => '/v1/admin/core/user/login',
                'apiAdmin' => '/v1/admin/core/index/index',
                'apiMenuTrees' => '/v1/admin/core/menu/trees',
                'apiConfig' => '/v1/core/index/siteInfo',
                'apiUserInfo' => '/v1/core/user/info'
            ],
            'siteInfo' => [
                'title' => config("uiadmin.site.title"),
                'logo' => config("uiadmin.site.logo"),
                'logoTitle' => config("uiadmin.site.logoTitle")
            ]
        ]]);
    }

    /**
     * 后台首页
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function index()
    {
        // 系统信心
        $server_software = explode(' ', $_SERVER['SERVER_SOFTWARE']);
        try {
            $mysql_info = DB::select('SELECT VERSION() as mysql_version');
        } catch (\Exception $e) {
            $mysql_info = [['mysql_version' => 'none']];
        }

        // 首页自定义
        $dataList = [
            [
                'span' => 24,
                'type' => 'count',
                'content' => [
                    [
                        'item' => ['icon' => 'ivu-icon ivu-icon-md-contacts', 'bgColor' => '#2db7f5', 'title' => ''],
                        'current' => ['value' => 0, 'suffix' => ''],
                        'content' => ['value' => '注册用户']
                    ],
                    [
                        'item' => ['icon' => 'ivu-icon ivu-icon-md-person-add', 'bgColor' => '#19be6b', 'title' => ''],
                        'current' => ['value' => 0, 'suffix' => ''],
                        'content' => ['value' => '今日新增']
                    ],
                    [
                        'item' => ['icon' => 'ivu-icon ivu-icon-md-clock', 'bgColor' => '#ff9900', 'title' => ''],
                        'current' => ['value' => isset($pay_total) ? $pay_total : 0, 'suffix' => ''],
                        'content' => ['value' => isset($pay_total) ? '总消费' : '本周新增']
                    ],
                    [
                        'item' => ['icon' => 'ivu-icon ivu-icon-ios-paper-plane', 'bgColor' => '#ed4014', 'title' => ''],
                        'current' => ['value' => isset($pay_today_count) ? $pay_today_count : 0, 'suffix' => ''],
                        'content' => ['value' => isset($pay_today_count) ? '今日消费' : '本月新增']
                    ]
                ]
            ],
            [
                'span' => 12,
                'type' => 'card',
                'title' => '系统信息',
                'content' => [
                    [
                        'type'  => 'text',
                        'title' => '服务器IP',
                        'value' => isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '0.0.0.0' 
                    ],
                    [
                        'type'  => 'text',
                        'title' => 'WEB服务器',
                        'value' => php_uname('s').php_uname('r') . '(' .$server_software[0] . ')'
                    ],
                    [
                        'type'  => 'text',
                        'title' => 'PHP版本信息',
                        'value' => PHP_VERSION . ' 上传限制：' . ini_get('upload_max_filesize')
                    ],
                    [
                        'type'  => 'text',
                        'title' => '数据库信息',
                        'value' => config('database.type') . ' ' .$mysql_info[0]['mysql_version']
                    ],
                    [
                        'type'  => 'text',
                        'title' => '服务器时间',
                        'value' => date("Y-m-d G:i:s")
                    ],
                    // [
                    //     'type'  => 'divider',
                    //     'title' => '开发框架'
                    // ],
                    [
                        'type'  => 'text',
                        'title' => '框架',
                        'value' => "Laravel9"
                    ],
                    [
                        'type'  => 'text',
                        'title' => '后台框架',
                        'value' => "UiAdmin" . ' (v' . get_config('version') . ')'
                    ],
                    [
                        'type'  => 'text',
                        'title' => '官方网站',
                        'value' => 'https://jiangruyi.com(ijry@qq.com)'
                    ]
                ]
            ],
            [
                'span' => 12,
                'type' => 'card',
                'title' => '项目信息',
                'content' => [
                    [
                        'type'  => 'text',
                        'title' => '项目名称',
                        'value' => config('uiadmin.site.title')
                    ],
                    [
                        'type'  => 'text',
                        'title' => '项目口号',
                        'value' => config('uiadmin.site.slogan')
                    ],
                    [
                        'type'  => 'text',
                        'title' => '项目简介',
                        'value' => config('uiadmin.site.description')
                    ],
                    [
                        'type'  => 'text',
                        'title' => 'ICP备案号',
                        'value' => config('uiadmin.site.icp')
                    ]
                ]
            ]
        ];

        // 返回数据
        return response()->json(['code' => 200, 'msg' => '成功', 'data' => [
            'dataList' => $dataList
        ]]);
    }
}
