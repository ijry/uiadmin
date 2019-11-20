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

// [ 应用入口文件 ]
namespace think;

// 请求头
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: * ");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type,Authorization,CloudId,Eid");

/**
 * 在检测到option请求的时候就停止继续执行
 */
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    exit;
}

// 判断thinkphp存在
if (!is_dir('../thinkphp')) {
    echo '请先执行composer install安装依赖！';
    exit;
}

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();
