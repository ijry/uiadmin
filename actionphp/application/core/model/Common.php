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

use think\Model;
use think\model\concern\SoftDelete;

class Common extends Model
{
    protected $pk = 'id';

    public static function init()
    {
        self::event('before_insert', function ($model) {
            if (env('read_only') == true) {
                $model->error = '数据库无写入权限';
                return false;
            }
        });
        self::event('before_update', function ($model) {
            if (env('read_only') == true) {
                $model->error = '数据库无写入权限';
                return false;
            }
        });
        self::event('before_write', function ($model) {
            if (env('read_only') == true) {
                $model->error = '数据库无写入权限';
                return false;
            }
        });
        self::event('before_delete', function ($model) {
            if (env('read_only') == true) {
                $model->error = '数据库无写入权限';
                return false;
            }
        });
    }
}