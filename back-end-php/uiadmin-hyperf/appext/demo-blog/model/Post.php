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
namespace demo\blog\model;

use uiadmin\core\model\BaseModel;

class Post extends BaseModel
{

    /**
     * 与模型关联的数据表.
     *
     * @var ?string
     */
    protected ?string $table =  'xy_demo_blog_post';

}
