<?php
/**
 * +----------------------------------------------------------------------
 * | xyapi [ 渐进式云接口 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2020 http://jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
 * | 版权申明：此源码不是一个自由软件，是jry推出的私有源码，严禁在未经许可的情况下
 * | 拷贝、复制、传播、使用此源码的任意代码，如有违反，请立即删除，否则您将面临承担相应
 * | 法律责任的风险。如果需要取得官方授权，请联系官方QQ598821125。
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
     * @author jry <598821125@qq.com>
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
     * @author jry <598821125@qq.com>
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
     * @author jry <598821125@qq.com>
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
