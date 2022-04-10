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
namespace uiadmin\core\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use uiadmin\core\model\CamelCaseTrait;

abstract class BaseModel extends Model
{
    // use CamelCaseTrait;

    /**
     * 如果id不是自增，改为false。
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * ID 的数据类型。
     *
     * @var string
     */
    // protected $keyType = 'string';

    // 自定义用于存储时间戳的字段的名称
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    /**
     * 指示模型是否主动维护时间戳。
     *
     * @var bool
     */
    public $timestamps = false;

    public static $snakeAttributes = false;

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    // public function getAttribute($key)
    // {
    //     if (array_key_exists($key, $this->relations)) {
    //         return parent::getAttribute($key);
    //     } else {
    //         return parent::getAttribute(Str::snake($key));
    //     }
    // }

    // /**
    //  * Set a given attribute on the model.
    //  *
    //  * @param  string  $key
    //  * @param  mixed  $value
    //  * @return mixed
    //  */
    // public function setAttribute($key, $value)
    // {
    //     return parent::setAttribute(Str::snake($key), $value);
    // }
}
