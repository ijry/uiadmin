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

namespace app\core\controller;

use think\Db;
use think\Request;
use app\core\controller\common\Home;

/**
 * 用户控制器
 *
 * @author jry <ijry@qq.com>
 */
class User extends Home
{
    private $core_user;
    private $core_identity;
    private $core_login;

    public function __construct()
    {
        $this->core_user = new \app\core\model\User();
        $this->core_identity = new \app\core\model\Identity();
        $this->core_login = new \app\core\model\Login();
    }

    /**
    * 重置密码
    *
    * @return \think\Response
    * @author jry <ijry@qq.com>
    */
    public function restPassword()
    {
        $ret = $this->is_login();
        if ($ret['code'] == 200) {
            $old_password = I('post.old_password');
            $new_password = I('post.new_password');
            $re_password = I('post.re_password');
            $user_info = $this->core_user->where('id', $ret['data']['data']->uid)->find()
            if (!$user_info) {
                return json(['code' => 0, 'msg' => '不存在该用户', 'data' => []]);
            }
            if ($user_info['password'] != user_md5($old_password, $user_info['key']) {
                return json(['code' => 0, 'msg' => '旧密码输入错误', 'data' => []]);
            }
            if (!$new_password) {
                return json(['code' => 0, 'msg' => '请输入新密码', 'data' => []]);
            }
            if (!$re_password) {
                return json(['code' => 0, 'msg' => '请重复输入新密码', 'data' => []]);
            }
            if ($new_password != $old_password) {
                return json(['code' => 0, 'msg' => '新密码输入不一致', 'data' => []]);
            }

            // 启动事务
            try {
                $ret = $this->core_user->save(
                    ['password' => user_md5($new_password, $user_info['key'])],
                    ['id' => $ret['data']['data']->uid]
                );
                if (!$ret) {
                    Db::rollback();
                    return json(['code' => 0, 'msg' => '重置失败失败' . $this->core_user->getError(), 'data' => []]);
                }
                $ret1 = $this->core_login
                    ->where('uid', $ret['data']['data']->uid)
                    ->delete();
                if ($ret1) {
                    // 提交事务
                    Db::commit();
                    return json(['code' => 200, 'msg' => '密码重置成功，请重新登录！', 'data' => []]);
                } else {
                    return json(['code' => 0, 'msg' => '重置失败失败', 'data' => []]);
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
        } else {
            return json($ret);
        }
    }
}
