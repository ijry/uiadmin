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
/**
 * 调试打印函数
 *
 * @param [type] $str
 * @return void
 */
function p($str)
{
    echo '<pre>';
    print_r($str);exit;
    echo '</pre>';
}
// 获取UUID
function get_uuid() {
    $uuid1_object = \Ramsey\Uuid\Uuid::uuid1();
    $uuid1 = $uuid1_object->getTimeHiAndVersionHex()
        . $uuid1_object->getTimeMidHex()
        . $uuid1_object->getTimeLowHex()
        . $uuid1_object->getClockSeqHiAndReservedHex()
        . $uuid1_object->getClockSeqLowHex()
        . $uuid1_object->getNodeHex();
    return $uuid1;
}

/**
 * 转驼峰法
 * @param  array $data
 * @return array
 * @author jry <ijry@qq.com>
 */
function key2camel($array) {
    $data = [];
    foreach ($array as $key => $value) {
        if (\think\helper\Str::contains($key, '_')) {
            $key = \think\helper\Str::camel($key);
        }
        if (is_array($value)) {
            $value = key2camel($value);
        } else if (is_object($value)) {
            $value = key2camel($value->toArray());
        }
        $data[$key] = $value;
    }
    return $data;
}

/**
 * 登录检查
 * @param  string $str 要加密的字符串
 * @return string
 * @author jry <ijry@qq.com>
 */
function is_login()
{
    try {
        // 获取token
        $token = Request::header('Authorization');
        if (!$token) {
            $token = session('Authorization'); // 支持session
        }
        $user_service = new \app\core\service\User();
        $ret = $user_service->isLogin($token);
        return (Array)$ret['data'];
    } catch (Exception $e) {
        return false;
    }
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string
 * @author jry <ijry@qq.com>
 */
function user_md5($str, $auth_key)
{
    return !$str ? false : md5(sha1($str) . $auth_key);
}

/**
 * 毫秒时间戳
 * @return int
 * @author jry <ijry@qq.com>
 */
function micro_time()
{
    list($t1, $t2) = explode(' ', microtime());
    return (float) sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
}

/**
 * 时间戳格式化
 * @param int $time
 * @return string 完整的时间显示
 * @author jry <ijry@qq.com>
 */
function time_format($time = null, $format = 'Y-m-d H:i')
{
    if (!$time) {
        return '';
    } else {
        return date($format, intval($time));
    }
}

/**
 * 根据配置类型解析配置
 * @param  string $type  配置类型
 * @param  string  $value 配置值
 */
function parse_attr($value, $type = '')
{
    switch ($type) {
        default:
            //callback:callable:param
            //param为字符串，建议不带引号，暂不支持数组
            if (strpos($value, 'callback') === 0) {
                $value_explode = explode(':', $value);
                if (count($value_explode) == 2) {
                    list($flag, $func_name) = $value_explode;
                    $func_param             = '';
                } elseif (count($value_explode) == 3) {
                    list($flag, $func_name, $func_param) = $value_explode;
                }

                //防止参数被引号包裹出错
                $func_param = trim($func_param, "'\"");
                //callable形式如为D('Admin/User')->select
                if (strpos($func_name, '->')) {
                    $func_arr   = explode('->', $func_name);
                    $model_name = trim($func_arr[0], "D('\")");
                    $call_arr[] = db($model_name);
                    $call_arr[] = $func_arr[1];
                    return call_user_func($call_arr, $func_param);
                } else {
                    //callable形式如time
                    return call_user_func($func_name, $func_param);
                }
            }

            //function(){}匿名函数,暂不支持
            if (strpos($value, 'function') === 0) {
                return;
            }

            //解析"1:1:1\r\n2:3:4"格式字符串为数组
            $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
            if (strpos($value, ':')) {
                $value = array();
                foreach ($array as $val) {
                    $tmp = explode(':', $val);
                    switch (count($tmp)) {
                        case '2':
                            list($k, $v) = $tmp;
                            $value[$k]   = $v;
                            break;
                        case '3':
                            $value[$tmp[0]] = $tmp;
                            break;
                    }
                }
            } else {
                $value = $array;
            }

            break;
    }
    return $value;
}
