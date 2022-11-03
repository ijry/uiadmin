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
declare(strict_types=1);

namespace uiadmin\core\model;

use Hyperf\DbConnection\Model\Model;

abstract class BaseModel extends Model
{

    /**
     * 如果id不是自增，改为false。
     *
     * @var bool
     */
    public bool $incrementing = true;

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
    public bool $timestamps = false;

    public static bool $snakeAttributes = false;

    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    // public function getAttribute($key)
    // {
    //     $value = '';
    //     if (array_key_exists($key, $this->relations)) {
    //         $value = parent::getAttribute($key);
    //     } else {
    //         $value = parent::getAttribute(Str::snake($key));
    //     }
    //     return $value;
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
    //     if ($value == null) {
    //         return true;
    //     }
    //     return parent::setAttribute(Str::snake($key), $value);
    // }
}
