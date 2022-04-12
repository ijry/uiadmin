<?php
// 应用公共文件
use Illuminate\Support\Facades\Request;

// 以下兼容TP写法
function input($name) {
    if (\uiadmin\core\util\Str::contains('.', $name)) {
        $name = explode('.', $name);
        return Illuminate\Support\Facades\Request::input($name[1]);
    } else {
        return Illuminate\Support\Facades\Request::input($name);
    }
}
function json($data) {
    return response()->json($data);
}
/**
 * 当前URL地址中的scheme参数
 * @access public
 * @return string
 */
function scheme(): string
{
    return isSsl() ? 'https' : 'http';
}
/**
 * 当前是否ssl
 * @access public
 * @return bool
 */
function isSsl(): bool
{
    if (isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) {
        return true;
    } elseif (isset($_SERVER['REQUEST_SCHEME']) && 'https' == $_SERVER['REQUEST_SCHEME']) {
        return true;
    } elseif ('443' == $_SERVER['SERVER_PORT']) {
        return true;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO']) {
        return true;
    }
    // elseif ($this->httpsAgentName && $_SERVER($this->httpsAgentName)) {
    //     return true;
    // }

    return false;
}

function array_get($array, $key, $default = null)
{
    if (is_null($key)) {
        return $array; 
    }

    if (isset($array[$key])) {
        return $array[$key];
    }

    foreach (explode('.', $key) as $segment) {
        if (! is_array($array) || ! array_key_exists($segment, $array)) {
            return value($default);
        }

        $array = $array[$segment];
    }
    return $array;
}

/**
 * 后去扩展模块的服务
 *
 * @author jry <ijry@qq.com>
 */
function get_ext_services($service_list = [])
{
    $dir = base_path() . '/extention/';
    $file_arr = scandir($dir);
    $new_arr = [];
    foreach($file_arr as $item){
        if($item!=".." && $item !="."){
            if(is_dir($dir."/".$item)){
                $new_arr[] = $dir.$item;
            }
        }
    }
    $psr4 = [];
    foreach ($new_arr as $key => $value) {
        $source = $value . '/composer.json';
        if (is_file($source)) {
            $content = json_decode(file_get_contents($source), true);
            if (isset($content['extra']['laravel']['providers'])
                && count($content['extra']['laravel']['providers']) > 0) {
                foreach ($content['extra']['laravel']['providers'] as $key => $value1) {
                    $service_list[] = '\\' . $value1;
                }
            }
            if (isset($content['autoload']['psr-4'])) {
                foreach ($content['autoload']['psr-4'] as $key => $value1) {
                    $psr4[$key] = $value . '/' . $value1;
                }
            }
            if (isset($content['autoload']['files'])) {
                foreach ($content['autoload']['files'] as $key => $value1) {
                    if (is_file($value . '/' . $value1)) {
                        include $value . '/' . $value1;
                    }
                }
            }
        }
    }

    // 注册psr-4
    spl_autoload_register(function ($class) use($psr4) {
        /* 限定类名路径映射 */
        $class_map = $psr4;
        $tmp = explode('\\', $class);
        if (isset($tmp[1])) {
            $key = $tmp[0] . '\\' . $tmp[1] . '\\';
            if (isset($class_map[$key])) {
                unset($tmp[0]);
                unset($tmp[1]);
                $file = $class_map[$key] . implode('/', $tmp) . '.php';
                if (file_exists($file)) {
                    include $file;
                }
            }
        }
    });

    return $service_list;
}

// 内部配置
function get_config($name){
    $configs = [
        'framework' => 'Laravel9',
        'version' => '1.2.0',
        'xyadmin' => [
            'version' => '1.2.0'
        ]
    ];
    $names = explode('.', $name);
    switch (count($names)) {
        case 1:
            return $configs[$names[0]];
            break;
        case 2:
            return $configs[$names[0]][$names[1]];
            break;
    }
}

/**
 * 把用户输入的文本转义（主要针对特殊符号和emoji表情）
 */
function user_text_encode($str){
    if(!is_string($str))return $str;
    if(!$str || $str=='undefined')return '';
    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i",function($str){
        return addslashes($str[0]);
    },$text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
    return json_decode($text);
}

/**
 * 解码上面的转义
 */
function user_text_decode($str){
    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback('/\\\\\\\\/i',function($str){
        return '\\';
    },$text); //将两条斜杠变成一条，其他不动
    return json_decode($text);
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
        if (\app\core\util\Str::contains($key, '_')) {
            $key = \app\core\util\Str::camel($key);
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
 * 转下划线法
 * @param  array $data
 * @return array
 * @author jry <ijry@qq.com>
 */
function key2snake($array) {
    $data = [];
    foreach ($array as $key => $value) {
        $key = \app\core\util\Str::snake($key);
        if (is_array($value)) {
            $value = key2snake($value);
        } else if (is_object($value)) {
            $value = key2snake($value->toArray());
        }
        $data[$key] = $value;
    }
    return $data;
}

/**
 * 登录检查
 * @param  string $str 要加密的字符串
 * @return string
 * @author jry <598821125@qq.com>
 */
function is_login()
{
    try {
        // 获取token
        $token = Request::header('Authorization');
        if (!$token) {
            $token = session('Authorization'); // 支持session
        }
        $userService = new \uiadmin\core\service\User();
        $ret = $userService->isLogin($token);
        return (Array)$ret['data'];
    } catch (Exception $e) {
        return false;
    }
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string
 * @author jry <598821125@qq.com>
 */
function user_md5($str, $auth_key)
{
    return user_pwd_md5($str, $auth_key);
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string
 * @author jry <598821125@qq.com>
 */
function user_pwd_md5($str, $auth_key)
{
    return !$str ? false : md5(sha1($str) . $auth_key);
}

/**
 * 毫秒时间戳
 * @return int
 * @author jry <598821125@qq.com>
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
 * @author jry <598821125@qq.com>
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
 * 过滤标签，输出纯文本
 * @param string $str 文本内容
 * @return string 处理后内容
 * @author jry <598821125@qq.com>
 */
function html2text($str)
{
    $str     = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU", "", $str);
    $alltext = "";
    $start   = 1;
    for ($i = 0; $i < strlen($str); $i++) {
        if ($start == 0 && $str[$i] == ">") {
            $start = 1;
        } else if ($start == 1) {
            if ($str[$i] == "<") {
                $start = 0;
                $alltext .= " ";
            } else if (ord($str[$i]) > 31) {
                $alltext .= $str[$i];
            }
        }
    }
    $alltext = str_replace("　", " ", $alltext);
    $alltext = preg_replace("/&([^;&]*)(;|&)/", "", $alltext);
    $alltext = preg_replace("/[ ]+/s", " ", $alltext);
    return $alltext;
}


/**
 * 生成订单号
 * @return string 订单号
 * @author jry <598821125@qq.com>
 */
function orders_no() {
    // 生成24位唯一订单号码，格式：YYYY-MMDD-HHII-SS-NNNN,NNNN-CC，
    // 其中：YYYY=年份，MM=月份，DD=日期，HH=24格式小时，II=分，SS=秒，NNNNNNNN=随机数，CC=检查码
    // 订购日期
    $order_date = date('Y-m-d');
    // 订单号码主体（YYYYMMDDHHIISSNNNNNNNN）
    $order_id_main = date('YmdHis') . rand(10000000,99999999);
    // 订单号码主体长度
    $order_id_len = strlen($order_id_main);
    $order_id_sum = 0;
    for ($i = 0; $i < $order_id_len; $i++) {
        $order_id_sum += (int)(substr($order_id_main, $i, 1));
    }
    // 唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
    $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);
    return $order_id;
}

/**
 * 将xml转为array
 * @param string $xml
 * @throws WxPayException
 */
function xml2array($xml)
{
    if (!$xml) {
        return false;
    }
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    $array = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $array;
}

/**
 * 输出xml字符
 * @throws WxPayException
 **/
function array2xml($array)
{
    if (!is_array($array)
        || count($array) <= 0) {
        return false;
    }

    $xml = "<xml>";
    foreach ($array as $key => $val) {
        if (is_numeric($val)) {
            $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
        } else {
            $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
    }
    $xml .= "</xml>";
    return $xml;
}

/**
 * 根据配置类型解析配置
 * @param  string $type  配置类型
 * @param  string  $value 配置值
 */
function parse_attr($value, $type = '')
{
    if (!$value) {
        return $value;
    }
    switch ($type) {
        default:
            // json格式
            $_array = json_decode($value, true);
            if (is_array($_array)) {
                return $_array;
            }

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
                //callable形式如为core/Module->getChildList
                if (strpos($func_name, '->')) {
                    $func_arr   = explode('->', $func_name);
                    $call_arr[] = controller($func_arr[0], 'service');
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

/**
 * 字符串截取(中文按2个字符数计算)，支持中文和其他编码
 * @static
 * @access public
 * @param str $str 需要转换的字符串
 * @param str $start 开始位置
 * @param str $length 截取长度
 * @param str $charset 编码格式
 * @param str $suffix 截断显示字符
 * @return str
 */
function cut_str($str, $start, $length, $suffix = true, $charset = 'utf-8') {
    $str    = trim($str);
    $length = $length * 2;
    if ($length) {
        //截断字符
        $wordscut = '';
        if (strtolower($charset) == 'utf-8') {
            //utf8编码
            $n   = 0;
            $tn  = 0;
            $noc = 0;
            while ($n < strlen($str)) {
                $t = ord($str[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    $n++;
                    $noc++;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif (224 <= $t && $t < 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    $n++;
                }
                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }
            $wordscut = substr($str, 0, $n);
        } else {
            for ($i = 0; $i < $length - 1; $i++) {
                if (ord($str[$i]) > 127) {
                    $wordscut .= $str[$i] . $str[$i + 1];
                    $i++;
                } else {
                    $wordscut .= $str[$i];
                }
            }
        }
        if ($wordscut == $str) {
            return $str;
        }
        return $suffix ? trim($wordscut) . '...' : trim($wordscut);
    } else {
        return $str;
    }
}

//PHP stdClass Object转array  
function object2array($array) {  
    if(is_object($array)) {  
        $array = (array)$array;  
     } if(is_array($array)) {  
        foreach($array as $key=>$value) {  
             $array[$key] = object2array($value);  
        }  
     }  
     return $array;  
}

/**
 * 格式化数字
 */
function format_number($number){
    $length = strlen($number);  //数字长度
    if($length > 12){ //万亿单位
        $str = substr_replace(strstr($number,substr($number,-11),' '),'.',-1,0)."万亿";
    } elseif ($length > 8){ //亿单位
        $str = substr_replace(strstr($number,substr($number,-7),' '),'.',-1,0)."亿";
    } elseif ($length >4){ //万单位
        //截取前俩为
        $str = substr_replace(strstr($number,substr($number,-3),' '),'.',-1,0)."万";
    } else {
        return $number;
    }
    return $str;
}
