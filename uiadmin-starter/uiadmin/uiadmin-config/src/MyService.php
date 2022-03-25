<?php

namespace uiadmin\config;

use think\Route;
use think\Service;
use think\Validate;
use uiadmin\config\model\Config as ConfigModel;

class MyService extends Service
{
    public function boot()
    {
        // 获取数据库中的配置覆盖配置文件的配置
        $dataList = ConfigModel::where('status', '=' , 1)
            ->where('application', '=' , 'uiadmin')
            ->where('profile', '=' , 'prod')
            ->where('label', '=' , 'main')
            ->column('value', 'name');
        foreach ($dataList as $name => $value) {
            $name_array = explode('.', $name);
            if (isset($name_array[0])) {
                $config = config($name_array[0]);
                if ($config) {
                    if (count($name_array) == 2) {
                        $config[$name_array[1]] = $value;
                    } else  if (count($name_array) == 3) {
                        $config[$name_array[1]][$name_array[2]] = $value;
                    } else  if (count($name_array) == 4) {
                        $config[$name_array[1]][$name_array[2]][$name_array[3]] = $value;
                    }
                    config($config , $name_array[0]);
                }
            }
        }

        $this->registerRoutes(function (Route $route) {
        });
    }
}