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

use think\facade\Cache;
use app\core\controller\common\Admin;

/**
 * 系统功能控制器
 *
 * @author jry <ijry@qq.com>
 */
class System extends Admin
{
    protected function initialize()
    {
        parent::initialize();
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
            return json(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return json(['code' => 0, 'msg' => '删除错误', 'data' => []]);
        }
    }
}
