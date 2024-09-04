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
namespace uiadmin\auth\model;

use uiadmin\core\model\BaseModel;

class Role extends BaseModel
{

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected ?string $table =  'xy_auth_role';

    /**
     * 应进行类型转换的属性
     *
     * @var array
     */
    protected array $casts = [
        'policys' => 'array',
    ];

}
