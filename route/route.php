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
Route::rule('/', 'core/common.Index/index'); // 首页访问路由

// 合并各个模块的路由配置
$_module_conf_list = [];
$module_list = [0 => ['name' => 'core']];
$_api_pat = 'api/'; // API根路径
$_api_pat = 'api/';
foreach ($module_list as $key => $value) {
    $conf = require_once Env::get('app_path') . $value['name'] . '/install/install.php';
    $_module_conf_list[$value['name']] = $conf;
    foreach ($conf['route'] as $key0 => $value0) {
        if (isset($value0['rule']) && is_array($value0['rule'])) {
            foreach ($value0['rule'] as $key1 => $value1) {
                Route::rule($_api_pat.$key0.'/'.$value['name'].'/'.$key1, $value['name'].'/'.$value1[0]);
            }
        }
        if (isset($value0['resource']) && is_array($value0['resource'])) {
            foreach ($value0['resource'] as $key2 => $value2) {
                Route::rule($_api_pat.$key0.'/'.$value['name'].'/'.$key2, $value['name'].'/'.$value2[0]);
            }
        }
        if (isset($value0['alias']) && is_array($value0['alias'])) {
            foreach ($value0['alias'] as $key3 => $value3) {
                // 别名路由不支持根路径
                if (strpos($value3[1], '\\') == 0) {
                    Route::alias($key3, $value3[0]);
                } else {
                    Route::alias($key3, $value['name'].'/'.$value3[0]);
                }
            }
        }
    }
}
//dump(Route::getNames());
// 缓存所有模块的配置
cache('_module_conf_list', $_module_conf_list);

return [

];
