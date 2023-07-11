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

use uiadmin\core\model\BaseModel;

class Config extends BaseModel
{

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected ?string  $table =  'xy_config';

    /**
     * 应进行类型转换的属性
     *
     * @var ?array
     */
    protected array $casts = [
        'options' => 'array',
    ];

}
