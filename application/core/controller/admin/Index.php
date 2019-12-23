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

namespace app\core\controller\admin;

use think\Db;
use think\Request;
use think\facade\Cache;
use app\core\controller\common\Admin;

/**
 * 默认控制器
 *
 * @author jry <ijry@qq.com>
 */
class Index extends Admin
{
    protected function initialize()
    {
        parent::initialize();
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
        $mysql_info = \think\Db::query('SELECT VERSION() as mysql_version');
        $config_service = new \app\core\service\Config();
        $config_core = $config_service->getValueByModule('core', [['is_dev', '=', 0]]);

        // 统计数据
        $user_model = new \app\core\model\User();
        $user_total_count = $user_model->where('cloud_alias', 0)->count();
        $user_today_count = $user_model
            ->where('cloud_alias', '=', 0)
            ->whereTime('register_time', 'today')
            ->count();
        $user_week_count = $user_model
            ->where('cloud_alias', '=', 0)
            ->whereTime('register_time', 'week')
            ->count();
        $user_month_count = $user_model
            ->where('cloud_alias', '=', 0)
            ->whereTime('register_time', 'month')
            ->count();
        $module_service = new \app\core\service\Module();
        if ($module_service->isExist('pay')) {
            $order_model = new \app\pay\model\Order();
            $pay_total_count = $order_model
                ->where('cloud_alias', 0)
                ->where('pay_time', '>', 0)
                ->sum('total_money');
            $pay_today_count = $order_model
                ->where('cloud_alias', '=', 0)
                ->where('pay_time', '>', 0)
                ->whereTime('pay_time', 'today')
                ->sum('total_money');
        }

        // 首页自定义
        $data_list = [
            [
                'span' => 24,
                'type' => 'count',
                'content' => [
                    [
                        'item' => ['icon' => 'ivu-icon ivu-icon-md-contacts', 'bgColor' => '#f8f8f8', 'title' => ''],
                        'current' => ['value' => $user_total_count, 'suffix' => ''],
                        'content' => ['value' => '注册用户']
                    ],
                    [
                        'item' => ['icon' => 'ivu-icon ivu-icon-md-person-add', 'bgColor' => '#f8f8f8', 'title' => ''],
                        'current' => ['value' => $user_today_count, 'suffix' => ''],
                        'content' => ['value' => '今日新增']
                    ],
                    [
                        'item' => ['icon' => 'ivu-icon ivu-icon-md-clock', 'bgColor' => '#f8f8f8', 'title' => ''],
                        'current' => ['value' => isset($pay_total_count) ? $pay_total_count : $user_week_count, 'suffix' => ''],
                        'content' => ['value' => isset($pay_total_count) ? '总消费' : '本周新增']
                    ],
                    [
                        'item' => ['icon' => 'ivu-icon ivu-icon-ios-paper-plane', 'bgColor' => '#f8f8f8', 'title' => ''],
                        'current' => ['value' => isset($pay_today_count) ? $pay_today_count : $user_month_count, 'suffix' => ''],
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
                        'value' => $_SERVER['SERVER_ADDR']
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
                        'title' => '接口框架',
                        'value' => 'XYCms系统 (v0.1.0)'
                    ],
                    [
                        'type'  => 'text',
                        'title' => '后台框架',
                        'value' => 'XYAdmin云后台 (v0.1.0)'
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
                        'value' => $config_core['title']
                    ],
                    [
                        'type'  => 'text',
                        'title' => '项目口号',
                        'value' => $config_core['slogan']
                    ],
                    [
                        'type'  => 'text',
                        'title' => '项目简介',
                        'value' => $config_core['description']
                    ],
                    [
                        'type'  => 'text',
                        'title' => 'ICP备案号',
                        'value' => $config_core['icp']
                    ]
                ]
            ]
        ];

        // 返回数据
        return $this->return(['code' => 200, 'msg' => '成功', 'data' => [
            'data_list' => $data_list
        ]]);
    }

    /**
     * 设置状态字段
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function setStatus()
    {
        $table = input('post.table');
        $field = input('post.field');
        $status = input('post.status');
        if (is_array($status)) {
            $status = implode(',', $status);
        }
        $key_val = input('post.key_val');
        $ret = Db::name($table)
            ->where('id', '=' ,$key_val)
            ->update([$field => $status]);
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '修改成功', 'data' => []]);
        }
        return $this->return(['code' => 0, 'msg' => '修改失败', 'data' => []]);
    }

    /**
     * 删除缓存
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function cleanRuntime()
    {
        $ret = Cache::clear();
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return $this->return(['code' => 0, 'msg' => '删除错误', 'data' => []]);
        }
    }
}
