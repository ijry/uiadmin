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

// 预设Uni官方自写常量（方便后续调用）
defined('UNI_WEB_PATH') ? '' : define('UNI_WEB_PATH',dirname(str_replace("\\",'/',__DIR__)));
defined('UNI_WEB_APP') ? '' : define('UNI_WEB_APP',UNI_WEB_PATH.'/application');
defined('UNI_WEB_CONF') ? '' : define('UNI_WEB_CONF',UNI_WEB_PATH.'/config');
defined('UNI_WEB_EXTEND') ? '' : define('UNI_WEB_EXTEND',UNI_WEB_PATH.'/extend');
defined('UNI_WEB_PUBLIC') ? '' : define('UNI_WEB_PUBLIC',UNI_WEB_PATH.'/public');
defined('UNI_WEB_ROUTE') ? '' : define('UNI_WEB_ROUTE',UNI_WEB_PATH.'/route');
defined('UNI_WEB_RUNTIME') ? '' : define('UNI_WEB_RUNTIME',UNI_WEB_PATH.'/runtime');
defined('UNI_WEB_TP') ? '' : define('UNI_WEB_TP',UNI_WEB_PATH.'/thinkphp');
defined('UNI_WEB_VENDOR') ? '' : define('UNI_WEB_VENDOR',UNI_WEB_PATH.'/vendor');

// 请求头
header("Content-type: text/html; charset=utf-8");
$origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
header('Access-Control-Allow-Origin: ' . $origin);
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type,Authorization,CloudId,Eid");

/**
 * 在检测到option请求的时候就停止继续执行
 */
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
{
    exit;
}

// 判断thinkphp存在
if (!is_dir(UNI_WEB_TP))
{
    echo '请先执行composer install安装依赖！';
    exit;
}
/**
 * 检测目录是否拥有写入权限
 * linux系统下可能会出现权限无法运行TP框架
 */
function check_dir_write()
{
    $dirList = [
        UNI_WEB_RUNTIME,
        UNI_WEB_PUBLIC,
        UNI_WEB_VENDOR,
    ];
    foreach ($dirList as $key => $dir)
    {
        if (is_writable($dir))
        {
            return true;
        }else{
            return basename($dir);
        }
    }
}
$noDirName = check_dir_write();
if (check_dir_write() !== true)
{
    echo "确保根目录【{$noDirName}】具有写入权限";exit;
}


// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();
