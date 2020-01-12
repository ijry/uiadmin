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

namespace app\core\behavior;

use think\Db;
use think\facade\Route;
use think\facade\Request;

/**
 * 路由行为扩展
 *
 * @author jry <ijry@qq.com>
 */
class Router
{
    // 行为逻辑
    public function run($params)
    {
        // 数据库全局前缀
        if (!defined('DB_PREFIX')) {
            define('DB_PREFIX', config('database.prefix'));
        }
    
        // 调试模式
        if (env('app_debug')) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        }

        // 只读模式
        if (!request()->isGet() && env('read_only') && !in_array(
                request()->path(),
                ['api/v1/admin/core/index/cleanRuntime',
                'api/v1/iadmin/core/index/cleanRuntime',
                'admin/core/user/login',
                'api/v1/admin/core/user/login',
                'core/user/login',
                'api/v1/core/user/login',
                'core/user/logout',
                'api/v1/core/user/logout']
            )) {
            echo json_encode(['code' => 0, 'msg' => '您只有只读权限', 'data' => []]);
            exit;
        }

        // 默认路由
        Route::rule(
            'core/install/step5',
            'core/install/step5',
            'GET|POST'
        );
        Route::rule(
            'api/v1/core/install/step5',
            'core/install/step5',
            'GET|POST'
        );
        if (!is_file(env('root_path') . '.env')) {
            for ($i = 1; $i  < 5; $i++) {
                // 前后端不分离路由
                Route::rule(
                    'core/install/step' . $i,
                    'core/install/step' . $i,
                    'GET|POST'
                );
                // 前后端分离路由
                Route::rule(
                    'api/v1/' . 'core/install/step' . $i,
                    'core/install/step' . $i,
                    'GET|POST'
                );
            }
            \think\facade\Url::root(request()->rootUrl() . '/index.php');
            if (!\think\helper\Str::startsWith(request()->path(), 'core/install')) {
                Route::rule('/', 'core/install/step1'); // 首页访问路由
                Route::rule('/:path', 'core/install/step1'); // 任意访问路由
            }
        } else {
            // 内置路由
            $module_service = new \app\core\service\Module();
            if ($module_service->isExist('cms')) {
                Route::rule('/', 'cms/index/index'); // 首页访问路由
            } else {
                Route::rule('/', request()->rootUrl() . '/api/.html'); // 首页访问路由
            }
            Route::rule('/api/', 'core/index/api'); // API访问路由
            Route::rule('/admin/', 'core/admin.index/index'); // 后台首页访问路由

            // 设置URL模式
            $url_model = Db::name('core_config')
                ->removeOption('where')
                ->where('module', '=' , 'core')
                ->where('name', '=' , 'url_model')
                ->value('value');
            switch ($url_model) {
                case 'rewrite': //URL重写模式
                    \think\facade\Url::root(request()->rootUrl() . '');
                    break;
                case 'pathinfo': //pathinfo模式
                    \think\facade\Url::root(request()->rootUrl() . '/index.php');
                    break;
                case 'compatible': // 兼容模式
                    \think\facade\Url::root(request()->rootUrl() . '/index.php?s=');
                    break;
                default:
                    \think\facade\Url::root(request()->rootUrl() . '/index.php');
                    break;
            }

            // 调用云后台
            Route::get('/xyadmin/$', function (\think\Request $request, \think\Response $response) {
                $seconds_to_cache = 86400 * 30;
                $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
                return $response
                    ->data(file_get_contents('http://admin.jiangruyi.com/' . $request->pathinfo()))
                    ->code(200)
                    ->expires($ts)
                    ->cacheControl("max-age=$seconds_to_cache")
                    ->contentType('text/html');
            });
            Route::get('/xyadmin/<name>', 'https://admin.jiangruyi.com/' . request()->pathinfo());

            // 计算后台API路由
            $data_list = Db::name('core_menu')
                ->removeOption('where')
                ->where('menu_layer', '=' , 'admin')
                ->where('menu_type', 'in' , '1,2,3')
                ->select();
            foreach ($data_list as $key => $val) {
                $path = explode('/', $val['path']);
                // 前后端不分离路由
                Route::rule(
                    '/admin' . $val['path'] . $val['api_suffix'],
                    $path[1] . '/admin.' . $path[2] . '/' . $path[3],
                    $val['api_method']
                );
                // 前后端分离路由
                Route::rule(
                    'api/' . $val['api_prefix'] . '/admin' . $val['path'] . $val['api_suffix'],
                    $path[1] . '/admin.' . $path[2] . '/' . $path[3],
                    $val['api_method']
                );
            }

            // 计算前台API路由
            $data_list = Db::name('core_menu')
                ->removeOption('where')
                ->where('menu_layer', '=' , 'home')
                ->where('menu_type', 'in' , '1,2,3')
                ->select();
            foreach ($data_list as $key => $val) {
                $path = explode('/', $val['path']);
                // 前后端不分离路由
                Route::rule(
                    $val['path'] . $val['api_suffix'],
                    $path[1] . '/' . $path[2] . '/' . $path[3],
                    $val['api_method']
                );
                // 前后端分离路由
                Route::rule(
                    'api/' . $val['api_prefix'] . $val['path'] . $val['api_suffix'],
                    $path[1] . '/' . $path[2] . '/' . $path[3],
                    $val['api_method']
                );
            }

            // 模板标签
            config('template.taglib_pre_load', 'app\core\taglib\Core,app\cms\taglib\Cms');
        }
    }
}
