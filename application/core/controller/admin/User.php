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
use think\Validate;
use think\facade\Request;
use app\core\controller\common\Admin;
use app\core\util\Tree;

/**
 * 用户管理
 *
 * @author jry <ijry@qq.com>
 */
class User extends Admin
{
    private $core_user;
    private $core_login;


    protected function initialize()
    {
        parent::initialize();
        $this->core_user = new \app\core\model\User();
        $this->core_login = new \app\core\model\Login();
    }

    /**
     * 用户列表
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function lists()
    {
        //用户列表
        $data_list = $this->core_user->select()->toArray();
        $tree      = new Tree();
        $data_list = $tree->list2tree($data_list);

        //构造动态页面数据
        $ia_dylist      = new \app\core\util\iadypage\IaDylist();
        $list_data = $ia_dylist->init()
            ->addTopButton('add', '添加用户', ['api' => '/v1/admin/core/user/add'])
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/core/user/edit', 'title' => '修改用户信息'])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/core/user/delete',
                'title' => '确认要删除该用户吗？',
                'modal_type' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p>删除后将清空绑定的所有登录验证记录</p>',
            ])
            ->addColumn('id' , 'ID', ['width' => '50px'])
            ->addColumn('nickname', '昵称', ['width' => '120px'])
            ->addColumn('username', '用户名', ['width' => '120px'])
            ->addColumn('mobile', '手机号', ['width' => '120px'])
            ->addColumn('email', '邮箱', ['width' => '120px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('right_button_list', '操作', [
                'minWidth' => '50px',
                'type' => 'template',
                'template' => 'right_button_list'
            ])
            ->getData();
        
        //返回数据
        return json([
                'code' => 200, 'msg' => '成功', 'data' => [
                    'data_list' => $data_list,
                    'list_data' => $list_data
                ]
            ]);
    }

    /**
     * 添加
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function add()
    {
        if (request()->isPost()) {
            //数据验证
            $validate = Validate::make([
                'nickname'  => 'require',
                'username' => 'require',
                'password' => 'require'
            ],
            [
                'nickname.require' => '昵称必须',
                'username.require' => '用户名必须',
                'password.require' => '密码必须'
            ]);
            $data = input('post.');
            if (!$validate->check($data)) {
                return json(['code' => 200, 'msg' => $validate->getError(), 'data' => []]);
            }

            //数据构造
            $data_db = $data;
            if (count($data_db) <= 0 ) {
                return json(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            $data_db['avatar'] = isset($data_db['avatar']) ? $data_db['avatar'] : '';
            $data_db['key'] = \think\helper\Str::random(64); //秘钥
            $data_db['password'] = user_md5($data_db['password'], $data_db['key']); // 密码不能明文需要加密存储
            $data_db['status']   = 1;
            $data_db['register_time']   = time();

            //存储数据
            $ret = $this->core_user->save($data_db);
            if ($ret) {
                return json(['code' => 200, 'msg' => '添加用户成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '添加用户失败:' . $this->core_user->getError(), 'data' => []]);
            }
        } else {
            //构造动态页面数据
            $ia_dyform      = new \app\core\util\iadypage\IaDyform();
            $form_data = $ia_dyform->init()
                ->setFormMethod('post')
                ->addFormItem('nickname', '昵称', 'text', '', [
                    'placeholder' => '请输入昵称',
                    'tip' => '昵称类似微信昵称可以重复不能用来登录系统'
                ])
                ->addFormItem('username', '用户名', 'text', '', [
                    'placeholder' => '请输入用户名',
                    'tip' => '用户名唯一不重复，可以用来登录系统'
                ])
                ->addFormItem('password', '密码', 'text', '', [
                    'placeholder' => '请输入用户密码',
                    'tip' => '密码必须要包含字母数字和符号中的两种'
                ])
                ->addFormRule('nickname', [
                    ['required' => true, 'message' => '请填写昵称', 'trigger' => 'change'],
                ])
                ->addFormRule('username', [
                    ['required' => true, 'message' => '请填写用户名', 'trigger' => 'change'],
                ])
                ->addFormRule('password', [
                    ['required' => true, 'message' => '请填写密码', 'trigger' => 'change'],
                ])
                ->setFormValues()
                ->getData();
            
            //返回数据
            return json([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'form_data' => $form_data
                ]
            ]);
        } 
    }

    /**
     * 修改
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function edit($id)
    {
        if (request()->isPut()) {
            //数据验证
            $validate = Validate::make([
                    'nickname'  => 'require',
                    'username' => 'require',
                ],
                [
                    'nickname.require' => '昵称必须',
                    'username.require' => '用户名必须',
                ]);
            $data = input('post.');
            if (!$validate->check($data)) {
                return json(['code' => 200, 'msg' => $validate->getError(), 'data' => []]);
            }

            //数据构造
            $data_db = $data;
            if (count($data_db) <= 0 ) {
                return json(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            if (isset($data_db['password'])) {
                $data_db['key'] = \think\helper\Str::random(64); //秘钥
                $data_db['password'] = user_md5($data_db['password'], $data_db['key']); // 密码不能明文需要加密存储
            }

            //存储数据
            $ret = $this->core_user->update($data_db, ['id' => $id]);
            if ($ret) {
                return json(['code' => 200, 'msg' => '修改用户信息成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '修改用户信息失败:' . $this->core_user->getError(), 'data' => []]);
            }
        } else {
            //用户信息
            $info = $this->core_user
                ->where('id', $id)
                ->find();

            //构造动态页面数据
            $ia_dyform      = new \app\core\util\iadypage\IaDyform();
            $form_data = $ia_dyform->init()
                ->setFormMethod('put')
                ->addFormItem('nickname', '昵称', 'text', $info['nickname'], [
                    'placeholder' => '请输入昵称',
                    'tip' => '昵称类似微信昵称可以重复不能用来登录系统'
                ])
                ->addFormItem('username', '用户名', 'text', $info['username'], [
                    'placeholder' => '请输入用户名',
                    'tip' => '用户名唯一不重复，可以用来登录系统'
                ])
                ->addFormItem('password', '密码', 'text', '', [
                    'placeholder' => '请输入用户密码',
                    'tip' => '密码必须要包含字母数字和符号中的两种'
                ])
                ->addFormRule('nickname', [
                    ['required' => true, 'message' => '请填写昵称', 'trigger' => 'change'],
                ])
                ->addFormRule('username', [
                    ['required' => true, 'message' => '请填写用户名', 'trigger' => 'change'],
                ])
                ->setFormValues()
                ->getData();
            
            //返回数据
            return json([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'form_data' => $form_data
                ]
            ]);
        } 
    }

    /**
     * 删除
     * 
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function delete($id)
    {
        //注销登录信息
        $ret = $this->core_login
                ->where('uid', $id)
                ->delete();

        //删除用户
        $ret = $this->core_user
            ->where(['id' => $id])
            ->find()
            ->delete();
        if ($ret) {
            return json(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return json(['code' => 0, 'msg' => '删除错误:' . $this->core_user->getError(), 'data' => []]);
        }
    }
}
