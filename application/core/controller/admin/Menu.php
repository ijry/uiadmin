<?php
/**
 * +----------------------------------------------------------------------
 * | UniAdmin [ 渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2020 http://uniadmin.jiangruyi.com All rights reserved.
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
use app\core\model\User as coreUserModel;
use app\core\model\Role as coreRoleModel;

/**
 * 菜单管理
 *
 * @author jry <ijry@qq.com>
 */
class Menu extends Admin
{
    private $core_menu;
    private $core_module;

    protected function initialize()
    {
        parent::initialize();
        $this->core_menu = new \app\core\model\Menu();
        $this->core_module = new \app\core\model\Module();
    }

    /**
     * 后台左侧导航列表
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function trees()
    {
        $login = $this->isLogin();
        $roles = explode(',' , coreUserModel::where('id', $login['uid'])
            ->value('roles'));
        $adminAuth_list = coreRoleModel::where('name', 'in', $roles)
            ->column('adminAuth');
        $adminAuth = [];
        foreach ($adminAuth_list as $k => $v) {
            $v = explode(',', $v);
            $adminAuth = $adminAuth + $v;
        }
        $adminAuth = array_unique($adminAuth);

        // 获取列表
        $dataList = $this->core_menu
            ->where('menuLayer', '=', 'admin')
            ->order('sortnum asc')
            ->select()->toArray();
        foreach ($dataList as $key => &$val) {
            if (!in_array('super_admin', $roles) && !in_array('/' . $val['apiPrefix'] . '/' . $val['menuLayer'] . $val['path'], $adminAuth)) {
                unset($dataList[$key]);
                continue;
            }
            if ($roles == ['super_admin'] && \think\helper\Str::contains($val['path'], '.')) {
                unset($dataList[$key]);
                continue;
            }
            if ($val['menuType'] > 0 && $val['menuType'] < 4) {
                $val['adminApi'] = '/' . $val['apiPrefix'] . '/admin' . $val['path'] . $val['apiSuffix'];
            }
        }
        $tree      = new Tree();
        $menuTree = $tree->list2tree($dataList, 'path', 'pmenu', 'children', 0, false);

        // 获取站点信息
        $configService = new \app\core\service\Config();
        $siteInfo = $configService->getValueByModule('core');
        if (isset($siteInfo['domains'])) {
            $siteInfo['domains'] = explode(',', $siteInfo['domains']);
        }
        $menuTree[0] = array_merge($menuTree[0], $siteInfo);

        // 构造动态页面数据
        $xyBuilderList = new \app\core\util\xybuilder\XyBuilderList();
        $listData = $xyBuilderList->init()
            ->addTopButton('add', '添加菜单', ['api' => '/v1/admin/core/menu/add'])
            ->addRightButton('doc', '文档', ['api' => '/v1/admin/core/api/doc', 'width' => '1000', 'title' => 'API文档编辑', 'apiSuffix' =>['id']])
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/core/menu/edit', 'title' => '修改菜单'])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/core/menu/delete',
                'title' => '确认要删除该菜单吗？',
                'modalType' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p>删除菜单不可恢复</p>',
            ])
            ->addColumn('id' , 'ID', ['width' => '50px'])
            ->addColumn('module', '所属模块', ['width' => '80px'])
            ->addColumn('title', '菜单标题', ['width' => '230px'])
            ->addColumn('menuType', '类型', ['width' => '50px'])
            ->addColumn('apiMethod', '请求方法', ['width' => '90px'])
            ->addColumn('adminApi', '后台接口', ['width' => '250px'])
            ->addColumn('isHide', '隐藏', ['width' => '50px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('rightButtonList', '操作', [
                'type' => 'template',
                'template' => 'rightButtonList'
            ])
            ->setDataList($menuTree)
            ->getData();

        // 返回数据
        return $this->return(['code' => 200, 'msg' => '成功', 'data' => [
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
            $validate = Validate::make([
                'module'  => 'require',
                'title' => 'require',
                'menuType' => 'require',
                'path' => 'require',
                'apiPrefix' => 'require',
            ],
            [
                'module.require' => '请选择模块',
                'title.require' => '菜单名称必须',
                'menuType.require' => '菜单类型必须',
                'path.require' => '接口路径必须',
                'apiPrefix.require' => '接口前缀必须',
            ]);
            $data = input('post.');
            if (!$validate->check($data)) {
                return $this->return(['code' => 0, 'msg' => $validate->getError(), 'data' => []]);
            }

            // 数据构造
            $data_db = $data;
            if (count($data_db) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            $data_db['menuLayer'] = 'admin';
            $data_db['apiMethod'] = implode('|', $data_db['apiMethod']);

            // 存储数据
            $ret = $this->core_menu->save($data_db);
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '添加成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '添加失败' . $this->core_menu->getError(), 'data' => []]);
            }
        } else {
            // 获取模块列表
            $module_list = $this->core_module
                ->where('status', 1)
                ->order('sortnum asc')
                ->select()->toArray();
            $module_list_select = [];
            foreach ($module_list as $key => $val) {
                $module_list_select[$key]['title'] = $val['title'];
                $module_list_select[$key]['value'] = $val['name'];
            }

            // 获取菜单基于标题的树状列表
            $menu_list = $this->core_menu
                ->where('menuType', '<', 4)
                ->order('sortnum asc')
                ->select()->toArray();
            $tree      = new Tree();
            $menuTree = $tree->array2tree($menu_list, 'title', 'path', 'pmenu', 0, false);
            $menuTree_select = [];
            foreach ($menuTree as $key1 => $val1) {
                $menuTree_select[$key1]['title'] = $val1['title_show'];
                $menuTree_select[$key1]['value'] = $val1['path'];
            }

            // 构造动态页面数据
            $xyBuilderForm = new \app\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('post')
                ->addFormItem('module', '模块', 'select', '', [
                    'placeholder' => '请选择模块',
                    'tip' => '模块是一个可分享使用的最小功能包',
                    'options' => $module_list_select
                ])
                ->addFormItem('pmenu', '上级菜单', 'select', '', [
                    'placeholder' => '请选择上级菜单',
                    'tip' => '请选择上级菜单',
                    'options' => $menuTree_select
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
                ->addFormItem('routeType', '动态页面', 'radio', 1, [
                    'placeholder' => '请选择是否自动生成页面',
                    'tip' => '系统内容了动态页面技术，可以自动生成后台前端页面',
                    'options' => [
                        ['title' => 'Vue路由', 'value' => 'route',],
                        ['title' => 'iBuilder动态列表', 'value' => 'list',],
                        ['title' => 'iBuilder动态表单', 'value' => 'form',]
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
     * 修改
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function edit($id)
    {
        if(request()->isPut()){
            // 数据验证
            $validate = Validate::make([
                'module'  => 'require',
                'title' => 'require',
                'menuType' => 'require',
                'path' => 'require',
                'apiPrefix' => 'require',
            ],
            [
                'module.require' => '请选择模块',
                'title.require' => '菜单名称必须',
                'menuType.require' => '菜单类型必须',
                'path.require' => '接口路径必须',
                'apiPrefix.require' => '接口前缀必须',
            ]);
            $data = input('post.');
            if (!$validate->check($data)) {
                return $this->return(['code' => 0, 'msg' => $validate->getError(), 'data' => []]);
            }

            // 数据构造
            $data_db = $data;
            if (count($data_db) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            if (isset($data_db['apiMethod'])) {
                $data_db['apiMethod'] = implode('|', $data_db['apiMethod']);
            }

            // 存储数据
            try {
                $ret = $this->core_menu->save($data_db, ['id' => $id]);
            } catch (\Exception $e) {
                return $this->return(['code' => 0, 'msg' => '修改失败' . json_encode($e), 'data' => []]);
            }
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '修改成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '修改失败' . $this->core_menu->getError(), 'data' => []]);
            }
        } else {
            // 获取菜单信息
            // 用户信息
            $info = $this->core_menu
                ->where('id', $id)
                ->find();
            $info['apiMethod'] = explode('|', $info['apiMethod']);

            // 获取模块列表
            $module_list = $this->core_module
                ->where('status', 1)
                ->order('sortnum asc')
                ->select()->toArray();
            $module_list_select = [];
            foreach ($module_list as $key => $val) {
                $module_list_select[$key]['title'] = $val['title'];
                $module_list_select[$key]['value'] = $val['name'];
            }

            // 获取菜单基于标题的树状列表
            $menu_list = $this->core_menu
                ->where('menuType', '<', 4)
                ->order('sortnum asc')
                ->select()->toArray();
            $tree      = new Tree();
            $menuTree = $tree->array2tree($menu_list, 'title', 'path', 'pmenu', 0, false);
            $menuTree_select = [];
            foreach ($menuTree as $key => $val) {
                $menuTree_select[$key]['title'] = $val['title_show'];
                $menuTree_select[$key]['value'] = $val['path'];
            }

            // 构造动态页面数据
            $xyBuilderForm = new \app\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('put')
                ->addFormItem('module', '模块', 'select', '', [
                    'placeholder' => '请选择模块',
                    'tip' => '模块是一个可分享使用的最小功能包',
                    'options' => $module_list_select
                ])
                ->addFormItem('pmenu', '上级菜单', 'select', '', [
                    'placeholder' => '请选择上级菜单',
                    'tip' => '请选择上级菜单',
                    'options' => $menuTree_select
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
                ->addFormItem('apiMethod', '请求方法', 'checkbox', '', [
                    'placeholder' => '请勾选请求方法',
                    'tip' => '尽量符合Restful风格',
                    'options' => [
                        ['title' => 'GET', 'value' => 'GET',],
                        ['title' => 'POST', 'value' => 'POST',],
                        ['title' => 'PUT', 'value' => 'PUT',],
                        ['title' => 'DELETE', 'value' => 'DELETE',]
                    ]
                ])
                ->addFormItem('routeType', '动态页面', 'radio', 1, [
                    'placeholder' => '请选择是否自动生成页面',
                    'tip' => '系统内容了动态页面技术，可以自动生成后台前端页面',
                    'options' => [
                        ['title' => 'Vue路由', 'value' => 'route',],
                        ['title' => 'iBuilder动态列表', 'value' => 'list',],
                        ['title' => 'iBuilder动态表单', 'value' => 'form',]
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
            return $this->return([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'formData' => $formData
                ]
            ]);
        }
    }

    /**
     * 后台左侧导航列表路由规则
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function lists()
    {
        // 计算路由
        $dataList = $this->core_menu
            ->where('menuLayer', '=', 'admin')
            ->select();
        foreach ($dataList as $key => &$val) {
            $val['api'] = $val['apiPrefix'] . '/admin' . $val['path'];
        }
        return $this->return(['code' => 200, 'msg' => '成功', 'data' => ['dataList' => $dataList]]);
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
        $info = $this->core_menu
            ->where(['id' => $id])
            ->find();
        $exist = $this->core_menu
            ->where(['pmenu' => $info['path']])
            ->count();
        if ($exist > 0) {
            return $this->return(['code' => 0, 'msg' => '存在子菜单无法删除', 'data' => []]);
        }

        $ret = $this->core_menu
            ->where(['id' => $id])
            ->find()
            ->delete();
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return $this->return(['code' => 0, 'msg' => '删除错误' . $this->core_menu->getError(), 'data' => []]);
        }
    }
}
