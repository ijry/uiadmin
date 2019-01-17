<?php
// +----------------------------------------------------------------------
// | tpvue [ 模块化渐进式后台 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2019 http://tpvue.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------

namespace tpvue\core\controller\admin;

use think\facade\Request;
use tpvue\core\controller\admin\Admin;
use tpvue\core\util\Tree;

/**
 * 角色
 */
class AuthRole extends Admin
{
    /**
     * 角色列表
     *
     * @return \think\Response
     */
    public function lists()
    {
        $data_list = db('core_auth_role')
            ->where(['delete_time' => 0])
            ->select();
        $tree      = new Tree();
        $data_list = $tree->list2tree($data_list);
        //dump($data_list);
        return json(
            [
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'data_list' => $data_list
                ]
            ]
        );
    }

    /**
     * 删除
     * 
     * @return \think\Response
     */
    public function delete($id)
    {
        $ret = db('core_auth_role')
            ->where(['id' => $id])
            ->useSoftDelete('delete_time', time())
            ->delete();
        if ($ret) {
            return json(
                [
                    'code' => 200,
                    'msg' => '删除成功',
                    'data' => []
                ]
            );
        } else {
            return json(
                [
                    'code' => 200,
                    'msg' => '删除错误',
                    'data' => []
                ]
            );
        }
    }
}
