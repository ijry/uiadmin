<?php
/**
 * +----------------------------------------------------------------------
 * | uiadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/

namespace uiadmin\auth\admin;

use think\Request;
use think\facade\Db;
use uiadmin\core\admin\BaseAdmin;
use uiadmin\auth\model\Menu as MenuModel;
use uiadmin\auth\model\Role as RoleModel;

/**
 * 菜单控制器
 *
 * @author jry <ijry@qq.com>
 */
class Menu extends BaseAdmin
{
    /**
     * 后台左侧导航列表
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function trees()
    {
        // 获取用户角色
        $login = $this->isLogin();
        $class = config('uiadmin.user.driver');
        $userService = new $class();
        $userInfo = $userService->getById($login['uid']);
        $roles = $userInfo['roles'];
        $adminAuth_list = RoleModel::where('name', 'in', $roles)
            ->get('policys');
        $adminAuth = [];
        foreach ($adminAuth_list as $k => $v) {
            $v = explode(',', $v);
            $adminAuth = $adminAuth + $v;
        }
        $adminAuth = array_unique($adminAuth);

        // 获取列表
        $dataList = MenuModel::where('menu_layer', '=', 'admin')
            ->order('sortnum asc,id asc')
            ->get()->toArray();
        // 下面的处理存粹是为了后台界面显示的，API使用本接口是以下数据是没用的。
        foreach ($dataList as $key => &$val) {
            if (!in_array('super_admin', $roles) && !in_array('/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . $val['path'], $adminAuth)) {
                unset($dataList[$key]);
                continue;
            }
            if ($roles == ['super_admin'] && \uiadmin\core\util\Str::contains($val['path'], '.')) {
                unset($dataList[$key]);
                continue;
            }
            if ($val['routeType'] == 'iframe') {
                $val['adminApi'] = $val['apiParams'];
            } else {
                if ($val['menuType'] > -1 && $val['menuType'] < 4) {
                    $val['adminApi'] = '/' . $val['apiPrefix'] . '/admin' . $val['path'] . $val['apiSuffix'];
                }
            }
        }
        $tree = new \uiadmin\core\util\Tree();
        $menuTree = $tree->list2tree($dataList, 'path', 'pmenu', 'children', 0, false);

        // 获取站点信息
        $siteInfo = config('uiadmin.site');
        // $siteInfo['domains'] = explode(',', $siteInfo['domains']);
        $menuTree[0] = array_merge($menuTree[0], $siteInfo);

        // 构造动态页面数据
        $xyBuilderList = new \uiadmin\core\util\xybuilder\XyBuilderList();
        $listData = $xyBuilderList->init()
            ->addTopButton('add', '添加菜单', ['api' => '/v1/admin/auth/menu/add'])
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/auth/menu/edit', 'title' => '修改菜单'])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/auth/menu/delete',
                'title' => '确认要删除该菜单吗？',
                'modalType' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p>删除菜单不可恢复</p>',
            ])
            ->addColumn('id' , 'ID', ['width' => '150px'])
            ->addColumn('module', '所属模块', ['width' => '120px'])
            ->addColumn('title', '菜单标题', ['width' => '280px'])
            ->addColumn('menuType', '类型', ['width' => '50px'])
            ->addColumn('apiMethod', '请求方法', ['width' => '90px'])
            ->addColumn('adminApi', '后台接口', ['width' => '250px'])
            ->addColumn('apiExt', '接口后缀', ['width' => '50px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('isHide' , '显示', [
                'width' => '80px',
                'type' => 'template',
                'template' => 'switch',
                'options' => [1 => ['title' => '显示', 'value' => 0], 0 => ['title' => '隐藏', 'value' => 1]]
            ])
            ->addColumn('status' , '状态', [
                'width' => '80px',
                'type' => 'template',
                'template' => 'switch',
                'options' => [ 1 => '正常', 0 => '禁用']
            ])
            ->addColumn('rightButtonList', '操作', [
                'width' => '150px',
                'type' => 'template',
                'template' => 'rightButtonList'
            ])
            ->setConfig('listExpandAll', true)
            ->setTableName('auth_menu')
            ->setDataList($menuTree)
            ->getData();

        // 返回数据
        return json(['code' => 200, 'msg' => '成功', 'data' => [
            'listData' => $listData
        ]]);
    }

    /**
     * 添加
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function add()
    {
        if(request()->isPost()){
            // 数据验证
            $this->validateMake([
                'namespace'  => 'require',
                'module'  => 'require',
                'title' => 'require',
                'menuType' => 'require',
                'path' => 'require',
                'apiMethod' => 'require',
            ],
            [
                'module.require' => '请选择模块',
                'title.require' => '菜单名称必须',
                'menuType.require' => '菜单类型必须',
                'path.require' => '接口路径必须',
                'apiMethod.require' => '请求方法必须',
            ]);
            $data = input('post.');
            $this->validate($data);

            // 数据构造
            $dataDb = $data;
            if (count($dataDb) <= 0 ) {
                return json(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            $dataDb['menuLayer'] = 'admin';
            $dataDb['apiMethod'] = implode('|', $dataDb['apiMethod']);

            // 存储数据
            $ret = MenuModel::create($dataDb);
            if ($ret) {
                return json(['code' => 200, 'msg' => '添加成功', 'data' => [
                    'updateMenu' => true
                ]]);
            } else {
                return json(['code' => 0, 'msg' => '添加失败', 'data' => []]);
            }
        } else {
            // 获取模块列表
            // $moduleList = $this->core_module
            //     ->where('status', 1)
            //     ->order('sortnum asc')
            //     ->select()->toArray();
            // $moduleListSelect = [];
            // foreach ($moduleList as $key => $val) {
            //     $moduleListSelect[$key]['title'] = $val['title'];
            //     $moduleListSelect[$key]['value'] = $val['name'];
            // }

            // 获取菜单基于标题的树状列表
            $menuList = MenuModel::where('menu_layer', '=', 'admin')
                ->order('sortnum asc')
                ->select()->toArray();
            $tree      = new \uiadmin\core\util\Tree();
            $menuTree = $tree->array2tree($menuList, 'title', 'path', 'pmenu', 0, false);
            $menuTreeSelect = [];
            foreach ($menuTree as $key1 => $val1) {
                $menuTreeSelect[$key1]['title'] = $val1['title_show'];
                $menuTreeSelect[$key1]['value'] = $val1['path'];
            }

            // 构造动态页面数据
            $xyBuilderForm = new \uiadmin\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('post')
                ->addFormItem('namespace', '根命名空间', 'text', '', [
                    'placeholder' => '',
                    'tip' => '一般为扩展composer.json里定义的autoload.psr-4根命名空间',
                ])
                ->addFormItem('module', '模块名称', 'text', '', [
                    'placeholder' => '请选择模块',
                    'tip' => '模块是一个可分享使用的最小功能包',
                    //'options' => $moduleListSelect
                ])
                ->addFormItem('pmenu', '上级菜单', 'select', '', [
                    'placeholder' => '请选择上级菜单',
                    'tip' => '请选择上级菜单',
                    'options' => $menuTreeSelect
                ])
                ->addFormItem('icon', '菜单图标', 'text', '', [
                    'placeholder' => '请输入菜单图标',
                    'tip' => '菜单图标是显示在后台左侧菜单中的'
                ])
                ->addFormItem('title', '菜单标题', 'text', '', [
                    'placeholder' => '请输入菜单标题',
                    'tip' => '菜单标题是显示在左侧列表中的'
                ])
                ->addFormItem('tip', '菜单说明', 'text', '', [
                    'placeholder' => '请输入菜单说明',
                    'tip' => '好的说明有助于用户理解'
                ])
                ->addFormItem('menuType', '菜单类型', 'radio', '', [
                    'placeholder' => '请选择菜单类型',
                    'tip' => '请选择菜单类型',
                    'options' => [
                        ['title' => '分组', 'value' => 0],
                        ['title' => '左侧导航', 'value' => 1],
                        ['title' => '页面按钮', 'value' => 2],
                        ['title' => '纯接口', 'value' => 3]
                    ]
                ])
                ->addFormItem('path', '接口路径', 'text', '', [
                    'placeholder' => '请输入接口路径',
                    'tip' => '接口路径举例：/core/user/lists'
                ])
                ->addFormItem('apiPrefix', '接口前缀', 'text', 'v1', [
                    'placeholder' => '接口前缀',
                    'tip' => '一般默认v1'
                ])
                ->addFormItem('apiSuffix', '接口后缀', 'text', '', [
                    'placeholder' => '请输入接口后缀参数',
                    'tip' => '接口参数举例：/:id/:name'
                ])
                ->addFormItem('apiParams', '接口参数', 'text', '', [
                    'placeholder' => '请输入接口参数实际值',
                    'tip' => '接口参数的实际值举例：/core'
                ])
                ->addFormItem('apiMethod', '请求方法', 'checkbox', [], [
                    'placeholder' => '请勾选请求方法',
                    'tip' => '尽量符合Restful风格',
                    'options' => [
                        ['title' => 'GET', 'value' => 'GET',],
                        ['title' => 'POST', 'value' => 'POST',],
                        ['title' => 'PUT', 'value' => 'PUT',],
                        ['title' => 'DELETE', 'value' => 'DELETE',]
                    ]
                ])
                ->addFormItem('apiExt', '接口后缀', 'text', '', [
                    'placeholder' => '默认html可以自定义',
                    'tip' => '如：png'
                ])
                ->addFormItem('routeType', '动态页面', 'radio', 1, [
                    'placeholder' => '请选择是否自动生成页面',
                    'tip' => '系统内容了动态页面技术，可以自动生成后台前端页面',
                    'options' => [
                        ['title' => 'Vue路由', 'value' => 'route'],
                        ['title' => 'iframe', 'value' => 'iframe'],
                        ['title' => '自定义Vue组件', 'value' => 'remote'],
                        ['title' => 'Builder动态列表', 'value' => 'list'],
                        ['title' => 'Builder动态表单', 'value' => 'form'],
                        ['title' => 'Builder动态详情', 'value' => 'info'],
                        ['title' => 'Builder动态组合', 'value' => 'stack'],
                        ['title' => 'Builder动态文档', 'value' => 'doc']
                    ]
                ])
                ->addFormItem('isHide', '是否隐藏', 'radio', 0, [
                    'placeholder' => '请选择是否隐藏',
                    'tip' => '有时候一些功能不需要可以隐藏',
                    'options' => [
                        ['title' => '是', 'value' => 1,],
                        ['title' => '否', 'value' => 0,]
                    ]
                ])
                ->addFormItem('sortnum', '排序', 'text', '', [
                    'placeholder' => '请输入排序',
                    'tip' => '请输入排序'
                ])
                ->addFormRule('module', [
                    ['required' => true, 'message' => '请选择所属模块', 'trigger' => 'change'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写菜单标题', 'trigger' => 'blur'],
                ])
                ->addFormRule('menuType', [
                    ['required' => true, 'type' => 'number', 'message' => '请选择菜单类型', 'trigger' => 'change'],
                ])
                ->addFormRule('path', [
                    ['required' => true, 'message' => '请输入接口路径', 'trigger' => 'blur'],
                ])
                ->addFormRule('routeType', [
                    ['required' => true, 'type' => 'string', 'message' => '请选择是页面路由方式', 'trigger' => 'change'],
                ])
                ->addFormRule('isHide', [
                    ['required' => true, 'type' => 'number', 'message' => '请选择是否隐藏', 'trigger' => 'change'],
                ])
                ->setFormValues()
                ->getData();

            // 返回数据
            return json(
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
     * 修改
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function edit($id)
    {
        
        // 信息
        $info = MenuModel::where('id', $id)
            ->find();
        if(request()->isPut()){
            // 数据验证
            $this->validateMake([
                'module'  => 'require',
                'title' => 'require',
                'menuType' => 'require',
                'path' => 'require',
                'apiMethod' => 'require',
            ],
            [
                'module.require' => '请选择模块',
                'title.require' => '菜单名称必须',
                'menuType.require' => '菜单类型必须',
                'path.require' => '接口路径必须',
                'apiMethod.require' => '请求方法必须',
            ]);
            $data = input('post.');
            $this->validate($data);

            // 数据构造
            $dataDb = $data;
            if (count($dataDb) <= 0 ) {
                return json(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            if (isset($dataDb['apiMethod'])) {
                $dataDb['apiMethod'] = implode('|', $dataDb['apiMethod']);
            }

            // 更新数据
            foreach ($dataDb as $key => $value) {
                if (isset($info[$key])) {
                    $info[$key] = $value;
                }
            }

            // 存储数据
            $ret = $info->save();
            if ($ret) {
                return json(['code' => 200, 'msg' => '修改成功', 'data' => [
                    'updateMenu' => true
                ]]);
            } else {
                return json(['code' => 0, 'msg' => '修改失败', 'data' => []]);
            }
        } else {
            $info['apiMethod'] = explode('|', $info['apiMethod']);

            // 获取模块列表
            // $moduleList = $this->core_module
            //     ->where('status', 1)
            //     ->order('sortnum asc')
            //     ->select()->toArray();
            // $moduleListSelect = [];
            // foreach ($moduleList as $key => $val) {
            //     $moduleListSelect[$key]['title'] = $val['title'];
            //     $moduleListSelect[$key]['value'] = $val['name'];
            // }

            // 获取菜单基于标题的树状列表
            $menuList = MenuModel::where('menu_layer', '=', 'admin')
                ->order('sortnum asc')
                ->select()->toArray();
            $tree      = new \uiadmin\core\util\Tree();
            $menuTree = $tree->array2tree($menuList, 'title', 'path', 'pmenu', 0, false);
            $menuTreeSelect = [];
            foreach ($menuTree as $key => $val) {
                $menuTreeSelect[$key]['title'] = $val['title_show'];
                $menuTreeSelect[$key]['value'] = $val['path'];
            }

            // 构造动态页面数据
            $xyBuilderForm = new \uiadmin\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('put')
                ->addFormItem('namespace', '根命名空间', 'text', '', [
                    'placeholder' => '',
                    'tip' => '一般为扩展composer.json里定义的autoload.psr-4根命名空间',
                ])
                ->addFormItem('module', '模块', 'text', '', [
                    'placeholder' => '请选择模块',
                    'tip' => '模块是一个可分享使用的最小功能包',
                    // 'options' => $moduleListSelect
                ])
                ->addFormItem('pmenu', '上级菜单', 'select', '', [
                    'placeholder' => '请选择上级菜单',
                    'tip' => '请选择上级菜单',
                    'options' => $menuTreeSelect
                ])
                ->addFormItem('icon', '菜单图标', 'text', '', [
                    'placeholder' => '请输入菜单图标',
                    'tip' => '菜单图标是显示在后台左侧菜单中的'
                ])
                ->addFormItem('title', '菜单标题', 'text', '', [
                    'placeholder' => '请输入菜单标题',
                    'tip' => '菜单标题是显示在左侧列表中的'
                ])
                ->addFormItem('tip', '菜单说明', 'text', '', [
                    'placeholder' => '请输入菜单说明',
                    'tip' => '好的说明有助于用户理解'
                ])
                ->addFormItem('menuType', '菜单类型', 'radio', '', [
                    'placeholder' => '请选择菜单类型',
                    'tip' => '请选择菜单类型',
                    'options' => [
                        ['title' => '分组', 'value' => 0],
                        ['title' => '左侧导航', 'value' => 1],
                        ['title' => '页面按钮', 'value' => 2],
                        ['title' => '纯接口', 'value' => 3]
                    ]
                ])
                ->addFormItem('path', '接口路径', 'text', '', [
                    'placeholder' => '请输入接口路径',
                    'tip' => '接口路径举例：/core/user/lists'
                ])
                ->addFormItem('apiPrefix', '接口前缀', 'text', 'v1', [
                    'placeholder' => '接口前缀',
                    'tip' => '一般默认v1'
                ])
                ->addFormItem('apiSuffix', '接口后缀', 'text', '', [
                    'placeholder' => '请输入接口后缀参数',
                    'tip' => '接口参数举例：/:id/:name'
                ])
                ->addFormItem('apiParams', '接口参数', 'text', '', [
                    'placeholder' => '请输入接口参数实际值',
                    'tip' => '接口参数的实际值举例：/core'
                ])
                ->addFormItem('apiMethod', '请求方法', 'checkbox', [], [
                    'placeholder' => '请勾选请求方法',
                    'tip' => '尽量符合Restful风格',
                    'options' => [
                        ['title' => 'GET', 'value' => 'GET',],
                        ['title' => 'POST', 'value' => 'POST',],
                        ['title' => 'PUT', 'value' => 'PUT',],
                        ['title' => 'DELETE', 'value' => 'DELETE',]
                    ]
                ])
                ->addFormItem('apiExt', '接口后缀', 'text', '', [
                    'placeholder' => '默认html可以自定义',
                    'tip' => '如：png'
                ])
                ->addFormItem('routeType', '动态页面', 'radio', 1, [
                    'placeholder' => '请选择是否自动生成页面',
                    'tip' => '系统内容了动态页面技术，可以自动生成后台前端页面',
                    'options' => [
                        ['title' => 'Vue路由', 'value' => 'route'],
                        ['title' => 'iframe', 'value' => 'iframe'],
                        ['title' => '自定义Vue组件', 'value' => 'remote'],
                        ['title' => 'Builder动态列表', 'value' => 'list'],
                        ['title' => 'Builder动态表单', 'value' => 'form'],
                        ['title' => 'Builder动态详情', 'value' => 'info'],
                        ['title' => 'Builder动态组合', 'value' => 'stack'],
                        ['title' => 'Builder动态文档', 'value' => 'doc']
                    ]
                ])
                ->addFormItem('isHide', '是否隐藏', 'radio', 0, [
                    'placeholder' => '请选择是否隐藏',
                    'tip' => '有时候一些功能不需要可以隐藏',
                    'options' => [
                        ['title' => '是', 'value' => 1,],
                        ['title' => '否', 'value' => 0,]
                    ]
                ])
                ->addFormItem('sortnum', '排序', 'text', '', [
                    'placeholder' => '请输入排序',
                    'tip' => '请输入排序'
                ])
                ->addFormRule('module', [
                    ['required' => true, 'message' => '请选择所属模块', 'trigger' => 'change'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写菜单标题', 'trigger' => 'blur'],
                ])
                ->addFormRule('menuType', [
                    ['required' => true, 'type' => 'number', 'message' => '请选择菜单类型', 'trigger' => 'change'],
                ])
                ->addFormRule('path', [
                    ['required' => true, 'message' => '请输入接口路径', 'trigger' => 'blur'],
                ])
                ->addFormRule('routeType', [
                    ['required' => true, 'type' => 'string', 'message' => '请选择是页面路由方式', 'trigger' => 'change'],
                ])
                ->addFormRule('isHide', [
                    ['required' => true, 'type' => 'number', 'message' => '请选择是否隐藏', 'trigger' => 'change'],
                ])
                ->setFormValues($info)
                ->getData();

            // 返回数据
            return json([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'formData' => $formData
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
        // 子菜单检测
        $info = MenuModel::where('menu_layer', '=', 'admin')
            ->where(['id' => $id])
            ->find();
        $exist = MenuModel::where('menu_layer', '=', 'admin')
            ->where(['pmenu' => $info['path']])
            ->count();
        if ($exist > 0) {
            return json(['code' => 0, 'msg' => '存在子菜单无法删除', 'data' => []]);
        }

        $ret = MenuModel::where('menu_layer', '=', 'admin')
            ->where(['id' => $id])
            ->delete();
        if ($ret) {
            return json(['code' => 200, 'msg' => '删除成功', 'data' => [
                'updateMenu' => true
            ]]);
        } else {
            return json(['code' => 0, 'msg' => '删除错误', 'data' => []]);
        }
    }
}
