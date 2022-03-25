<?php
/**
 * +----------------------------------------------------------------------
 * | uiadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2020 http://jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
 * | 
*/
namespace uiadmin\config\model;

use think\Model;
use think\model\concern\SoftDelete;

class Config extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table =  'xy_config';

    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;

    public static function init()
    {
        parent::init();
    }

    // Options
    public function setOptionsAttr($value)
    {
        $value = json_encode($value, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        return $value;
    }
    public function getOptionsAttr($value)
    {
        return json_decode($value, true);
    }
}
