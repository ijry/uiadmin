<?php
/**
 * +----------------------------------------------------------------------
 * | InitAdmin/actionphp [ InitAdmin渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2019 http://initadmin.net All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <ijry@qq.com>
 * +----------------------------------------------------------------------
*/
namespace app\core\model;

use app\core\model\Common;
use think\Model;
use think\model\concern\SoftDelete;

class Menu extends Common
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'ia_core_menu';

    public static function init()
    {
        parent::init();
    }

    // 软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;
}