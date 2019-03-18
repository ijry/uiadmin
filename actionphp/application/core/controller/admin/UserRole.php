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
 * 用户角色
 *
 * @author jry <ijry@qq.com>
 */
class UserRole extends Admin
{
    private $core_role;
    private $core_user;

    protected function initialize()
    {
        parent::initialize();
        $this->core_role = new \app\core\model\Role();
        $this->core_user = new \app\core\model\User();
    }

    /**
     * 角色成员列表
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function lists($name)
    {
        //成员列表
        $data_list = $this->core_user
            ->where('', 'EXP', Db::raw("FIND_IN_SET('$name', roles)"))
            ->select()->toArray();
        foreach ($data_list as $key => &$val) {
            $val['role_name'] = $name;
        }

        //构造动态页面数据
        $ibuilder_list = new \app\core\util\ibuilder\IbuilderList();
        $list_data = $ibuilder_list->init()
            ->addTopButton('add', '添加成员', ['api' => '/v1/admin/core/user_role/add/' . $name])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/core/user_role/delete',
                'api_suffix' => ['id', 'role_name'],
                'title' => '确认要删除该成员吗？',
                'modal_type' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p>删除后该用户将无法操作系统后台</p>',
            ])
            ->addColumn('id' , 'ID', ['width' => '50px'])
            ->addColumn('role_name', '角色', ['width' => '100px'])
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
        return json(
            [
                'code' => 200, 'msg' => '成功', 'data' => [
                    'data_list' => $data_list,
                    'list_data' => $list_data
                ]
            ]
        );
    }

    /**
     * 添加
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function add($name)
    {
        if(request()->isPost()){
            // 数据验证
            $validate = Validate::make([
                'uid'  => 'number',
                'role_name' => 'require'
            ],
            [
                'uid.number' => 'pid必须数字',
                'role_name.require' => '角色名称必须',
            ]);
            $data = input('post.');
            if (!$validate->check($data)) {
                return json(['code' => 200, 'msg' => $validate->getError(), 'data' => []]);
            }

            // 数据构造
            $user_info  = $this->core_user->where('id', $data['uid'])->find();
            if ($user_info['roles']) {
                $user_info['roles'] = explode(',', $user_info['roles']);
                $user_info['roles'] = array_unique(array_merge($user_info['roles'], [$data['role_name']]));
            } else {
                $user_info['roles'] = [$data['role_name']];
            }

            // 存储数据
            $ret = $this->core_user->save(
                ['roles' => implode(',', $user_info['roles'])],
                ['id' => $data['uid']]
            );
            if ($ret) {
                return json(['code' => 200, 'msg' => '添加角色成员成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '添加角色成员失败:' . $this->core_user->getError(), 'data' => []]);
            }
        } else {
            //构造动态页面数据
            $ibuilder_form = new \app\core\util\ibuilder\IbuilderForm();
            $form_data = $ibuilder_form->init()
                ->setFormMethod('put')
                ->addFormItem('role_name', '角色名称', 'text', $name, [
                    'placeholder' => '请输入角色名称',
                    'tip' => '角色名称',
                    'readonly' => true
                ])
                ->addFormItem('uid', 'UID', 'text', '', [
                    'placeholder' => '请输入uid',
                    'tip' => 'uid是用户唯一标识可以在用户列表查到'
                ])
                ->addFormRule('role_name', [
                    ['required' => true, 'message' => '请输入角色名称', 'trigger' => 'change'],
                ])
                ->addFormRule('uid', [
                    ['required' => true, 'message' => '请填写uid', 'trigger' => 'change'],
                ])
                ->setFormValues()
                ->getData();

            //返回数据
            return json(
                [
                    'code' => 200,
                    'msg' => '成功',
                    'data' => [
                        'form_data' => $form_data
                    ]
                ]
            );
        }
    }

    /**
     * 删除
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function delete($uid, $name)
    {
        if ($uid == 1) {
            return json(['code' => 0,'msg' => '超级管理员不允许删除','data' => []]);
        }
        $user_info  = $this->core_user->where('id', $uid)->find();
        if ($user_info['roles']) {
            $user_info['roles'] = explode(',', $user_info['roles']);
            foreach ($user_info['roles'] as $key => $val) {
                if ($val == $name) {
                    unset($user_info['roles'][$key]);
                }
            }
        }
        $ret = $this->core_user->save(
            ['roles' => implode(',', $user_info['roles'])],
            ['id' => $uid]
        );
        if ($ret) {
            return json(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return json(['code' => 0, 'msg' => '删除错误:' . $this->core_user->getError(), 'data' => []]);
        }
    }
}
