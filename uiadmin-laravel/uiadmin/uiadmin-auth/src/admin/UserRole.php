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
namespace uiadmin\auth\admin;

use think\facade\Db;
use think\Validate;
use think\facade\Request;
use uiadmin\core\admin\BaseAdmin;
use uiadmin\auth\model\User as UserModel;
use uiadmin\auth\model\Menu as MenuModel;
use uiadmin\auth\model\Role as RoleModel;

/**
 * 用户角色
 *
 * @author jry <ijry@qq.com>
 */
class UserRole extends BaseAdmin
{
    /**
     * 角色成员列表
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function lists($name)
    {
        // 成员列表
        $dataList = UserModel::where('', 'EXP', "FIND_IN_SET('$name', roles)")
            ->select()->toArray();
        foreach ($dataList as $key => &$val) {
            $val['roleName'] = $name;
        }

        // 构造动态页面数据
        $xyBuilderList = new \uiadmin\core\util\xybuilder\XyBuilderList();
        $listData = $xyBuilderList->init()
            ->addTopButton('add', '添加成员', [
                'pageType' => 'modal',
                'api' => '/v1/admin/auth/user_role/add/' . $name
            ])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/auth/user_role/delete',
                'apiSuffix' => ['id', 'roleName'],
                'title' => '确认要删除该成员吗？',
                'modalType' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p>删除后该用户将无法操作系统后台</p>',
            ])
            ->addColumn('id' , 'UID', ['width' => '50px'])
            ->addColumn('roleName', '角色', ['width' => '140px'])
            ->addColumn('nickname', '昵称', ['width' => '150px'])
            ->addColumn('username', '用户名', ['width' => '120px'])
            ->addColumn('mobile', '手机号', ['width' => '120px'])
            ->addColumn('email', '邮箱', ['width' => '120px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('rightButtonList', '操作', [
                'minWidth' => '50px',
                'type' => 'template',
                'template' => 'rightButtonList'
            ])
            ->setDataList($dataList)
            ->getData();

        //返回数据
        return $this->return(
            [
                'code' => 200, 'msg' => '成功', 'data' => [
                    'listData' => $listData
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
            $this->validateMake([
                'uid'  => 'number',
                'roleName' => 'require'
            ],
            [
                'uid.number' => 'pid必须数字',
                'roleName.require' => '角色名称必须',
            ]);
            $data = input('post.');
            $this->validate($data);

            // 数据构造
            $userInfo  = UserModel::where('id', $data['uid'])->find();
            if ($userInfo['roles']) {
                $userInfo['roles'] = array_unique(array_merge($userInfo['roles'], [$data['roleName']]));
            } else {
                $userInfo['roles'] = [$data['roleName']];
            }

            // 存储数据
            $ret = UserModel::update(
                ['roles' => $userInfo['roles']],
                ['id' => $data['uid']]
            );
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '添加角色成员成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '添加角色成员失败', 'data' => []]);
            }
        } else {
            $xyBuilderList = new \uiadmin\core\util\xybuilder\XyBuilderList();
            $pageData = $xyBuilderList->getRightButton('uid', '用户列表', [
                'modalType' => 'list',
                'api' => '/v1/admin/auth/user/lists',
                'width' => '1100'
            ]);
            // 构造动态页面数据
            $xyBuilderForm = new \uiadmin\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('post')
                ->addFormItem('roleName', '角色名称', 'text', $name, [
                    'placeholder' => '请输入角色名称',
                    'tip' => '角色名称',
                    'disabled' => true
                ])
                ->addFormItem('uid', '选择用户', 'selectlist', 0 ,[
                    'placeholder' => '联系人',
                    'tip' => 'uid是用户唯一标识可以在用户列表查到',
                    'pageData' => $pageData['pageData']
                ])
                ->addFormRule('roleName', [
                    ['required' => true, 'message' => '请输入角色名称', 'trigger' => 'change'],
                ])
                ->addFormRule('uid', [
                    ['required' => true, 'message' => '请填写uid', 'trigger' => 'change'],
                ])
                ->setFormValues()
                ->getData();

            // 返回数据
            return $this->return(
                [
                    'code' => 200,
                    'msg' => '成功',
                    'data' => [
                        'formData' => $formData
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
            return $this->return(['code' => 0,'msg' => '超级管理员不允许删除','data' => []]);
        }
        $userInfo  = UserModel::where('id', $uid)->find();
        if ($userInfo['roles']) {
            $userInfo['roles'] = explode(',', $userInfo['roles']);
            foreach ($userInfo['roles'] as $key => $val) {
                if ($val == $name) {
                    unset($userInfo['roles'][$key]);
                }
            }
        }
        $ret = UserModel::update(
            ['roles' => implode(',', $userInfo['roles'])],
            ['id' => $uid]
        );
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return $this->return(['code' => 0, 'msg' => '删除错误', 'data' => []]);
        }
    }
}
