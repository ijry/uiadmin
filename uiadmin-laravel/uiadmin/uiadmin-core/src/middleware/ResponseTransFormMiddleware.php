<?php

namespace uiadmin\core\middleware;

use Closure;

class ResponseTransFormMiddleware
{
    //下划线转驼峰
    public function handle($request, Closure $next)
    {
        
        $response = $next($request);
        if ($response->original) {
            return $this->changeHump($response->original);
        } else {
            return $this->changeHump([]);
        }
    }


    //转换驼峰(只转key)
    public function changeHump($params)
    {
        if (is_array($params)) {
            foreach ($params as $key=>$value){
                unset($params[$key]);
                $params[$this->convertUnderline($key)] = is_array($value)?$this->changeHump($value):$value;
            }
        }
        return $params;
    }

    public function convertUnderline($str)
    {
        return  preg_replace_callback('/([-_]+([a-z]{1}))/i', function ($matches) {return strtoupper($matches[2]);}, $str);
    }
}
