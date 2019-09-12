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

namespace app\core\controller\admin;

use think\Db;
use think\Request;
use think\facade\Cache;
use app\core\controller\common\Admin;

/**
 * 默认控制器
 *
 * @author jry <ijry@qq.com>
 */
class Index extends Admin
{
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 后台首页
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function index()
    {
        // 首页自定义
        $data_list = [];
        // $data_list[0] = [
        //     'span' => '12',
        //     'type' => 'html',
        //     'html' => ''
        // ];
        return $this->return(['code' => 200, 'msg' => '成功', 'data' => [
            'data_list' => $data_list
        ]]);
    }

    /**
     * 设置状态字段
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function setStatus()
    {
        $table = input('post.table');
        $field = input('post.field');
        $status = input('post.status');
        $key_val = input('post.key_val');
        $ret = Db::name($table)
            ->where('id', '=' ,$key_val)
            ->update([$field => $status]);
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '修改成功', 'data' => []]);
        }
        return $this->return(['code' => 0, 'msg' => '修改失败', 'data' => []]);
    }

    /**
     * 删除缓存
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function cleanRuntime()
    {
        $ret = Cache::clear();
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return $this->return(['code' => 0, 'msg' => '删除错误', 'data' => []]);
        }
    }
}
