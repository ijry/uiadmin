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

use Illuminate\Database\Eloquent\Casts\Attribute;
use uiadmin\core\model\BaseModel;

class Role extends BaseModel
{

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table =  'xy_auth_role';

    protected function policys(): Attribute
    {
        return new Attribute(
            get: fn ($value) => explode(',', $value),
            set: fn ($value) => implode(',', $value),
        );
    }

}
