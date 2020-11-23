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

/**
 * API管理
 *
 * @author jry <ijry@qq.com>
 */
class Api extends Admin
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
     * 修改API文档
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function doc($id)
    {
        if(request()->isPut()){
            // 获取数据
            $post_data = input('post.');

            // 处理数据
            $data = [];
            foreach ($post_data as $key => $value) {
                $key_ex = explode('_', $key);
                $data[$key_ex[0]][$key_ex[1]] = $value;
            }

            // 存储数据
            try{
                $ret = $this->core_menu->save(['doc' => json_encode($data, JSON_UNESCAPED_UNICODE)], ['id' => $id]);
            }catch(\Exception $e){
                return $this->return(['code' => 0, 'msg' => '修改失败' . json_encode($e), 'data' => []]);
            }
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '修改成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '修改失败', 'data' => []]);
            }
        } else {
            // 获取菜单信息
            $info = $this->core_menu
                ->where('id', $id)
                ->find();
            $info['doc'] = json_decode($info['doc'], true);
            $info['apiMethod'] = explode('|', $info['apiMethod']);
            $doc_info = [];
            foreach ($info['apiMethod'] as $key => $value) {
                if (isset($info['doc'][$value])) {
                    $doc_info[$value] = $info['doc'][$value];
                } else {
                    $doc_info[$value] = [
                        'description' => '',
                        'params' => [
                            0 => ['description' => '']
                        ]
                    ];
                }
            }
            if ($info['menuLayer'] == 'admin') {
                $entry = '';
            } else {
                $entry = '/admin';
            }

            // 构造动态页面数据
            $xyBuilderForm = new \app\core\util\xybuilder\XyBuilderForm();
            $xyBuilderForm->init()
                ->setFormMethod('put');
            foreach ($doc_info as $key => $value) {
                $xyBuilderForm->addFormItem($key . '_method', '请求地址', 'static', $key . '：/' . $info['apiPrefix'] . $entry .$info['path'] . $info['apiSuffix'])
                    ->addFormItem($key . '_description', '接口说明', 'text', $doc_info[$key]['description'])
                    ->addFormItem($key . '_params', '请求参数', 'formlist', $doc_info[$key]['params'], [
                        'options' => [
                            ['title' => '是否必须', 'key' => 'require', 'span' => 2],
                            ['title' => '参数名', 'key' => 'name', 'span' => 4],
                            ['title' => '参数标题', 'key' => 'title', 'span' => 4],
                            ['title' => '说明', 'key' => 'description', 'span' => 8],
                            ['title' => '示例', 'key' => 'example', 'span' => 4]
                        ]
                    ])
                    ->addFormItem($key . '_divider', '', 'divider', '');
            }
            $formData = $xyBuilderForm->setFormValues()
                ->getData();

            //返回数据
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
     * API树
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function trees()
    {
        // 计算路由
        $dataList = $this->core_menu
            ->where('deleteTime', '=' ,0)
            ->where('menuLayer', '=', 'home')
            ->order('sortnum asc')
            ->select()->toArray();
        foreach ($dataList as $key => &$val) {
            $val['api'] = '/' . $val['apiPrefix'] . $val['path'] . $val['apiSuffix'];
        }
        $tree      = new Tree();
        $menuTree = $tree->list2tree($dataList, 'path', 'pmenu', 'children', 0, false);

        //构造动态页面数据
        $xyBuilderList = new \app\core\util\xybuilder\XyBuilderList();
        $listData = $xyBuilderList->init()
            ->addTopButton('add', '添加', ['api' => '/v1/admin/core/api/add'])
            ->addRightButton('doc', '文档', ['api' => '/v1/admin/core/api/doc', 'width' => '1000', 'title' => 'API文档编辑', 'apiSuffix' =>['id']])
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/core/api/edit', 'title' => '修改API'])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/core/api/delete',
                'title' => '确认要删除该API吗？',
                'modalType' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p>删除菜单不可恢复</p>',
            ])
            ->addColumn('id' , 'ID', ['width' => '150px'])
            ->addColumn('module', '所属模块', ['width' => '80px'])
            ->addColumn('title', '菜单标题', ['width' => '200px'])
            ->addColumn('apiMethod', '请求方法', ['width' => '100px'])
            ->addColumn('api', '前台接口', ['minWidth' => '150px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('rightButtonList', '操作', [
                'minWidth' => '50px',
                'type' => 'template',
                'template' => 'rightButtonList'
            ])
            ->setDataList($menuTree)
            ->getData();

        //返回数据
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
            //数据验证
            $validate = Validate::make([
                'module'  => 'require',
                'title' => 'require',
                'path' => 'require',
                'apiPrefix' => 'require',
            ],
            [
                'module.require' => '请选择模块',
                'title.require' => '接口名称必须',
                'path.require' => '接口路径必须',
                'apiPrefix.require' => '接口前缀必须',
            ]);
            $data = input('post.');
            if (!$validate->check($data)) {
                return $this->return(['code' => 0, 'msg' => $validate->getError(), 'data' => []]);
            }

            //数据构造
            $data_db = $data;
            if (count($data_db) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            $data_db['menuLayer'] = 'home';
            $data_db['apiMethod'] = implode('|', $data_db['apiMethod']);

            //存储数据
            $ret = $this->core_menu->save($data_db);
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '添加成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '添加失败:' . $this->core_menu->getError(), 'data' => []]);
            }
        } else {
            //获取模块列表
            $module_list = $this->core_module
                ->where('status', 1)
                ->order('sortnum asc')
                ->select()->toArray();
            $module_list_select = [];
            foreach ($module_list as $key => $val) {
                $module_list_select[$key]['title'] = $val['title'];
                $module_list_select[$key]['value'] = $val['name'];
            }

            //获取菜单基于标题的树状列表
            $menu_list = $this->core_menu
                ->where('menuLayer', '=', 'home')
                ->order('sortnum asc')
                ->select()->toArray();
            $tree      = new Tree();
            $menuTree = $tree->array2tree($menu_list, 'title', 'path', 'pmenu', 0, false);
            $menuTree_select = [];
            foreach ($menuTree as $key1 => $val1) {
                $menuTree_select[$key1]['title'] = $val1['title_show'];
                $menuTree_select[$key1]['value'] = $val1['path'];
            }

            //构造动态页面数据
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
                ->addFormItem('title', '菜单标题', 'text', '', [
                    'placeholder' => '请输入菜单标题',
                    'tip' => '菜单标题是显示在左侧列表中的'
                ])
                ->addFormItem('tip', '菜单说明', 'text', '', [
                    'placeholder' => '请输入菜单说明',
                    'tip' => '好的说明有助于用户理解'
                ])
                ->addFormItem('menuType', '菜单类型', 'radio', 3, [
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
                        ['title' => 'Vue路由', 'value' => 'route'],
                        ['title' => 'iBuilder动态列表', 'value' => 'list'],
                        ['title' => 'iBuilder动态表单', 'value' => 'form'],
                        ['title' => 'iBuilder动态详情', 'value' => 'info'],
                        ['title' => 'iBuilder动态组合', 'value' => 'stack'],
                        ['title' => 'iBuilder动态文档', 'value' => 'doc']
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
                ->addFormRule('path', [
                    ['required' => true, 'message' => '请输入接口路径', 'trigger' => 'blur'],
                ])
                ->addFormRule('routeType', [
                    ['required' => true, 'type' => 'string', 'message' => '请选择是页面路由方式', 'trigger' => 'change'],
                ])
                ->setFormValues()
                ->getData();

            //返回数据
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
            //数据验证
            $validate = Validate::make([
                'module'  => 'require',
                'title' => 'require',
                'path' => 'require',
                'apiPrefix' => 'require',
            ],
            [
                'module.require' => '请选择模块',
                'title.require' => '菜单名称必须',
                'path.require' => '接口路径必须',
                'apiPrefix.require' => '接口前缀必须',
            ]);
            $data = input('post.');
            if (!$validate->check($data)) {
                return $this->return(['code' => 0, 'msg' => $validate->getError(), 'data' => []]);
            }

            //数据构造
            $data_db = $data;
            if (count($data_db) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            if (isset($data_db['apiMethod'])) {
                $data_db['apiMethod'] = implode('|', $data_db['apiMethod']);
            }

            // 存储数据
            try{
                $ret = $this->core_menu->save($data_db, ['id' => $id]);
            }catch(\Exception $e){
                return $this->return(['code' => 0, 'msg' => '修改失败' . json_encode($e), 'data' => []]);
            }
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '修改成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '修改失败:' . $this->core_menu->getError(), 'data' => []]);
            }
        } else {
            //获取菜单信息
            $info = $this->core_menu
                ->where('id', '=', $id)
                ->find();
            $info['apiMethod'] = explode('|', $info['apiMethod']);

            //获取模块列表
            $module_list = $this->core_module
                ->where('status', '=', 1)
                ->order('sortnum asc')
                ->select()->toArray();
            $module_list_select = [];
            foreach ($module_list as $key => $val) {
                $module_list_select[$key]['title'] = $val['title'];
                $module_list_select[$key]['value'] = $val['name'];
            }

            //获取菜单基于标题的树状列表
            $menu_list = $this->core_menu
                ->where('menuLayer', '=', 'home')
                ->where('id', '<>', $id)
                ->order('sortnum asc')
                ->select()->toArray();
            $tree      = new Tree();
            $menuTree = $tree->array2tree($menu_list, 'title', 'path', 'pmenu', 0, false);
            $menuTree_select = [];
            foreach ($menuTree as $key => $val) {
                $menuTree_select[$key]['title'] = $val['title_show'];
                $menuTree_select[$key]['value'] = $val['path'];
            }

            //构造动态页面数据
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
                ->addFormItem('title', '菜单标题', 'text', '', [
                    'placeholder' => '请输入菜单标题',
                    'tip' => '菜单标题是显示在左侧列表中的'
                ])
                ->addFormItem('tip', '菜单说明', 'text', '', [
                    'placeholder' => '请输入菜单说明',
                    'tip' => '好的说明有助于用户理解'
                ])
                ->addFormItem('menuType', '菜单类型', 'radio', 0, [
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
                        ['title' => 'Vue路由', 'value' => 'route'],
                        ['title' => 'iBuilder动态列表', 'value' => 'list'],
                        ['title' => 'iBuilder动态表单', 'value' => 'form'],
                        ['title' => 'iBuilder动态详情', 'value' => 'info'],
                        ['title' => 'iBuilder动态组合', 'value' => 'stack'],
                        ['title' => 'iBuilder动态文档', 'value' => 'doc']
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
                ->addFormRule('path', [
                    ['required' => true, 'message' => '请输入接口路径', 'trigger' => 'blur'],
                ])
                ->addFormRule('routeType', [
                    ['required' => true, 'type' => 'string', 'message' => '请选择是页面路由方式', 'trigger' => 'change'],
                ])
                ->setFormValues($info)
                ->getData();

            //返回数据
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
     * 删除
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function delete($id)
    {
        // 子菜单检测
        $info = $this->core_menu
            ->where('menuLayer', '=', 'home')
            ->where('id', '=', $id)
            ->find();
        $exist = $this->core_menu
            ->where('menuLayer', '=', 'home')
            ->where('pmenu', '=', $info['path'])
            ->count();
        if ($exist > 0) {
            return $this->return(['code' => 0, 'msg' => '存在子项目无法删除', 'data' => []]);
        }

        $ret = $this->core_menu
            ->where('menuLayer', '=', 'home')
            ->where('id', '=', $id)
            ->find()
            ->delete();
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return $this->return(['code' => 0, 'msg' => '删除错误:' . $this->core_menu->getError(), 'data' => []]);
        }
    }
}
