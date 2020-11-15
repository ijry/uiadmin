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

namespace app\core\service;

use think\Db;
use think\Validate;
use think\Model;

class Config extends Model
{
    protected $core_config;

    public function __construct()
    {
        $this->core_config = new \app\core\model\Config();
    }

    /**
     * 获取配置
     * @return array
     *
     * @author jry <ijry@qq.com>
     */
    public function getSettings($module_name)
    {
        if ($this->cloud_id === null) {
            throw new \Exception('未指定cloud_id', 0);
        }
        $module_config = $this->getValueByModule($module_name);
        if (isset($module_config['isOpen']) && $module_config['isOpen'] != 1) {
            throw new \Exception($module_name . '未开启', 0);
        }
        return $module_config;
    }

    /**
     * 获取模块配置
     * @return array
     *
     * @author jry <ijry@qq.com>
     */
    public function getListByModule($module_name)
    {
        $dataList = $this->core_config
            ->where('module', '=', $module_name)
            ->select();
        return $dataList;
    }

    /**
     * 获取模块配置
     * @return array
     *
     * @author jry <ijry@qq.com>
     */
    public function getValueByModule($module_name, $con = [])
    {
        // 查看是否存在配置分组
        $configCate = $this->core_config
            ->where('module', '=', $module_name)
            ->where('name', '=', 'configCate')
            ->find();
        if ($configCate) {
            $configCate_value = parse_attr($configCate['value']);
        }

        // 查询所有模块
        $con = array_merge($con, [['module', '=', $module_name]]);
        $dataList = $this->core_config
            ->field('configCate,configType,name,value')
            ->where($con)
            ->select();
        $return = [];

        // 如果配置分组大于2个说明有同名的平级配置，这里将结果返回为二维数组。
        if (isset($configCate_value)) {
            foreach ($dataList as $key => &$value) {
                if ($value['configType'] == 'array') {
                    if ($value['configCate'] == 'basic') {
                        $return[$value['name']] = parse_attr($value['value']);
                    } else {
                        $return[$value['configCate']][$value['name']] = parse_attr($value['value']);
                    }
                } else if (in_array($value['configType'], ['images', 'files'])) {
                    if ($value['value'] == '') {
                        $value['value'] = [];
                    } else {
                        $value['value'] = json_decode($value['value'], true);
                    }
                    if ($value['configCate'] == 'basic') {
                        $return[$value['name']] = $value['value'];
                    } else {
                        $return[$value['configCate']][$value['name']] = $value['value'];
                    }
                } else {
                    if ($value['configCate'] == 'basic') {
                        $return[$value['name']] = $value['value'];
                    } else {
                        $return[$value['configCate']][$value['name']] = $value['value'];
                    }
                }
            }
        } else {
            foreach ($dataList as $key => &$value) {
                if ($value['configType'] == 'array') {
                    $return[$value['name']] = parse_attr($value['value']);
                } else if (in_array($value['configType'], ['images', 'files'])) {
                    if ($value['value'] == '') {
                        $value['value'] = [];
                    } else {
                        $value['value'] = json_decode($value['value'], true);
                    }
                    $return[$value['name']] = $value['value'];
                } else {
                    $return[$value['name']] = $value['value'];
                }
            }
        }
        return $return;
    }
}
