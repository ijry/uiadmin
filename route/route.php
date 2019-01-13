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

// 系统默认路由
Route::rule('/', 'core/index/index'); // 首页访问路由

// 合并各个模块的路由配置
$_module_conf_list = [];
$module_list = [0 => ['name' => 'core']];
$_api_pat = 'api/'; // API根路径
foreach ($module_list as $key => $value) {
    $conf = require_once Env::get('app_path') . $value['name'] . '/tpvue/tpvue.php';
    $_module_conf_list[$value['name']] = $conf;
    if (isset($conf['route']['rule']) && is_array($conf['route']['rule'])) {
        foreach ($conf['route']['rule'] as $key1 => $value1) {
            Route::rule($_api_pat.$key1, $value1['name'].'/'.$value1[1]);
        }
    }
    if (isset($conf['route']['resource']) && is_array($conf['route']['resource'])) {
        foreach ($conf['route']['resource'] as $key2 => $value2) {
            Route::rule($_api_pat.$key2, $value2['name'].'/'.$value2[1]);
        }
    }
    if (isset($conf['route']['alias']) && is_array($conf['route']['alias'])) {
        foreach ($conf['route']['alias'] as $key3 => $value3) {
            // 别名路由不支持根路径
            if (strpos($value3[1], '\\') == 0) {
                Route::alias($$key3, $value3[1]);
            } else {
                Route::alias($$key3, $value['name'].'/'.$value3[1]);
            }
        }
    }
}
// 缓存所有模块的配置
cache('_module_conf_list', $_module_conf_list);

return [

];
