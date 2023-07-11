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

use Illuminate\Database\Eloquent\Casts\Attribute;
use uiadmin\core\model\BaseModel;

class Config extends BaseModel
{

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table =  'xy_config';

    protected function options(): Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
        );
    }

}
