<?php
/**
 * +----------------------------------------------------------------------
 * | xycloud [ 多租户渐进式后台云 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2020 http://jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
 * | 版权申明：此源码不是一个自由软件，是jry推出的私有源码，严禁在未经许可的情况下
 * | 拷贝、复制、传播、使用此源码的任意代码，如有违反，请立即删除，否则您将面临承担相应
 * | 法律责任的风险。如果需要取得官方授权，请联系官方QQ598821125。
*/
namespace uiadmin\auth\model;

use think\Model;
use think\model\concern\SoftDelete;

class User extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table =  'xy_auth_user';

    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;

    public static function init()
    {
        parent::init();
    }

    // roles
    public function setRolesAttr($value)
    {
        return implode(',', $value);
    }
    public function getRolesAttr($value)
    {
        return explode(',', $value);
    }

    // 软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;
}
