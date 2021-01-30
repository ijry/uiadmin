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

// [ 应用入口文件 ]
namespace think;

// 请求头
header("Content-type: text/html; charset=utf-8");
$origin = $_SERVER['HTTP_ORIGIN'] ? $origin = $_SERVER['HTTP_ORIGIN'] : '*';
header('Access-Control-Allow-Origin: ' . $origin);
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type,Authorization,CloudId,Eid");

/**
 * 在检测到option请求的时候就停止继续执行
 */
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
{
    echo '11';
    exit;
}

// 判断thinkphp存在
if (!is_dir("../thinkphp"))
{
    echo '请先执行composer install安装依赖！';
    exit;
}
/**
 * 检测目录是否拥有写入权限
 * linux系统下可能会出现权限无法运行TP框架
 */
if (!is_writable("../runtime"))
{
    echo "确保根目录 runtime 具有写入权限";
    exit;
}


// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();
