<?php
/**
 * +----------------------------------------------------------------------
 * | UiAdmin [ 渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <ijry@qq.com>
 * +----------------------------------------------------------------------
*/
namespace app\cms\model;

use app\core\model\Common;
use think\Model;
use think\model\concern\SoftDelete;

class Cate extends Common
{
    // 设置当前模型对应的完整数据表名称
    protected $table = DB_PREFIX . 'cms_cate';

    public static function init()
    {
        parent::init();
    }

    // 软删除
    use SoftDelete;
    protected $deleteTime = 'deleteTime';
    protected $defaultSoftDelete = 0;
}