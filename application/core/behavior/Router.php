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
            Route::rule('/', 'core/index/index'); // 首页访问路由
            Route::rule('/xyadmin/api$', 'core/index/api'); // API访问路由
            //Route::rule('/uniadmin$', 'core/admin.index/index'); // 后台首页访问路由

            $configService = new \app\core\service\Config();
            $siteInfo = $configService->getValueByModule('core');
            // 支持logo和favicon
            Route::get('/logo', $siteInfo['logo'])->ext('png');
            Route::get('/logoAdmin', $siteInfo['logoAdmin'])->ext('png');
            Route::get('/favicon', $siteInfo['favicon'])->ext('ico');

            // 设置URL模式
            $urlModel = Db::name('core_config')
                ->removeOption('where')
                ->where('module', '=' , 'core')
                ->where('name', '=' , 'urlModel')
                ->value('value');
            switch ($urlModel) {
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

            // 支持PWA
            Route::get('/admin/manifest', function() {
                $config_service = new \app\core\service\Config();
                $config_core = $config_service->getValueByModule('core', [['isDev', '=', 0]]);
                return json_encode([
                    'name' => $config_core['title'] . '后台',
                    'short_name' => $config_core['title'] . '后台',
                    'theme_color' => '#ffffff',
                    'background_color' => '#f1f1f1',
                    'start_url' => '/xyadmin/',
                    'display' => 'standalone',
                    'icons' => [
                        [
                            'src' => $config_core['logoAdmin'],
                            'sizes' => "192x192",
                            "type" => "image/png"
                        ],
                        [
                            'src' => $config_core['logoAdmin'],
                            'sizes' => "512x512",
                            "type" => "image/png"
                        ]
                    ]
                ]);
            })->ext('json');
            // 支持service-worker
            Route::get('/admin/service-worker', function(\think\Response $response) {
                $sw = <<<EOF
                self.addEventListener('install', (event) => {
                    console.log('Version installing', event);
                    event.waitUntil(
                        caches.open("v1").then(
                            // cache => cache.add("")
                        )
                    );
                });
                self.addEventListener('activate', (event) => {
                    console.log('Version now ready to handle');
                });
                self.addEventListener("fetch", event => {
                    const url = new URL(event.request.url);
                    console.log('fetch', event.request);
                });
EOF;
                return $response
                    ->data($sw)
                    ->code(200)
                    ->contentType('application/javascript');
            })->ext('js');

            // 调用云后台
            Route::get('/xyadmin$', request()->url(true) . '/');
            Route::get('/xyadmin/$', function (\think\Request $request, \think\Response $response) {
                $seconds_to_cache = 86400 * 30;
                $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
                $ch= curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://admin.jiangruyi.com/xyadmin/?version=' . config('app.admin_version')); // 支持调用不同版本便于官方升级不影响老项目
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 表示不检查证书
                $xyadminIndex = curl_exec($ch);
                curl_close($ch);
                return $response
                    ->data($xyadminIndex ? $xyadminIndex : '调用云后台出错') 
                    ->code(200)
                    ->expires($ts)
                    ->cacheControl("max-age=$seconds_to_cache")
                    ->contentType('text/html');
            });

            // 计算后台API路由
            $dataList = Db::name('core_menu')
                ->removeOption('where')
                ->where('menuLayer', '=' , 'admin')
                ->where('menuType', 'in' , '1,2,3')
                ->select();
            foreach ($dataList as $key => $val) {
                $path = explode('/', $val['path']);
                if (isset($path[3])) {
                    // 前后端不分离路由
                    Route::rule(
                        '/admin' . $val['path'] . $val['apiSuffix'],
                        $path[1] . '/admin.' . $path[2] . '/' . $path[3],
                        $val['apiMethod']
                    );
                    // 前后端分离路由
                    Route::rule(
                        'api/' . $val['apiPrefix'] . '/admin' . $val['path'] . $val['apiSuffix'],
                        $path[1] . '/admin.' . $path[2] . '/' . $path[3],
                        $val['apiMethod']
                    );
                }
            }

            // 计算前台API路由
            $dataList = Db::name('core_menu')
                ->removeOption('where')
                ->where('menuLayer', '=' , 'home')
                ->where('menuType', 'in' , '1,2,3')
                ->select();
            foreach ($dataList as $key => $val) {
                $path = explode('/', $val['path']);
                if (isset($path[3])) {
                    // 前后端不分离路由
                    Route::rule(
                        $val['path'] . $val['apiSuffix'],
                        $path[1] . '/' . $path[2] . '/' . $path[3],
                        $val['apiMethod']
                    )->ext('html');
                    // 前后端分离路由
                    Route::rule(
                        'api/' . $val['apiPrefix'] . $val['path'] . $val['apiSuffix'],
                        $path[1] . '/' . $path[2] . '/' . $path[3],
                        $val['apiMethod']
                    );
                }
            }

            // 引入composer2
            if (is_file(env('root_path') . 'vendor2/autoload.php')) {
                require_once env('root_path') . 'vendor2/autoload.php';
            }

            // 模板标签
            config('template.taglib_pre_load', 'app\core\taglib\Core,app\cms\taglib\Cms');
        }
    }
}
