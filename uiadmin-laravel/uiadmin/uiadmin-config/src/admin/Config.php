<?php
/**
 * +----------------------------------------------------------------------
 * | uiadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2020 http://jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
 * | 
*/

namespace uiadmin\config\admin;

use think\facade\Db;
use think\Validate;
use think\facade\Request;
use uiadmin\core\admin\BaseAdmin;
use uiadmin\core\util\Tree;
use uiadmin\config\model\Config as ConfigModel;

/**
 * 配置管理
 *
 * @author jry <ijry@qq.com>
 */
class Config extends BaseAdmin
{
     /**
     * 批量修改配置
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function saveBatch()
    {
        if (request()->isPut()) {
            $data = input('post.');

            // 数据构造
            $dataDb = $data;
            if (count($dataDb) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }

            if (env('read_only') == true) {
                return $this->return(['code' => 0, 'msg' => '无写入权限', 'data' => []]);
            }

            // 存储数据
            if ($dataDb && is_array($dataDb)) {
                foreach ($dataDb as $k => $v) {
                    // todo根据configType决定是json_encode还是implode
                    // 如果值是数组则转换成字符串，适用于复选框等类型
                    if (is_array($v)) {
                        if (isset($v[0]) && is_array($v[0])) {
                            $v = json_encode($v);
                        } else {
                            $v = implode(',', $v);
                        }
                    }
                    ConfigModel::where('name', $k)->update(['value' => $v]);
                }
                return $this->return(['code' => 200, 'msg' => '保存成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '保存失败', 'data' => []]);
            }
        } else {
            // 获取所有配置
            $configList = ConfigModel::order('sortnum asc')
                // ->where('module', '=', $module)
                ->where('status', '=', 1)
                ->select()->toArray();
            foreach ($configList as $key => &$value) {
                if (in_array($value['type'], ['images', 'files'])) {
                    $value['value'] = json_decode($value['value'], true);
                } elseif(in_array($value['type'], ['checkbox'])) {
                    $value['value'] = explode(',', $value['value']);
                }
            }

            // 构造动态页面数据
            $xyBuilderForm = new \uiadmin\core\util\xybuilder\XyBuilderForm();
            $xyBuilderForm->init()
                ->setFormMethod('put');
            if (isset($configList) && count($configList) > 0) {
                foreach ($configList as $key => $val) {
                    if ($val['options']) {
                        // $val['options'] = parse_attr($val['options']);
                    }
                    $xyBuilderForm->addFormItem(
                        $val['name'],
                        $val['title'],
                        $val['type'],
                        $val['value'],
                        [
                            'tip' => $val['tip'],
                            'placeholder' => $val['placeholder'],
                            'options' => $val['options'],
                            // 'isDev' => $val['isDev']
                        ]
                    );
                }
            }
            $xyBuilderForm->setFormValues();
            $formData = $xyBuilderForm->getData();

            // 返回数据
            return $this->return([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'formData' => $formData,
                    'noback' => true
                ]
            ]);
        }
    }

    /**
     * 列表
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function lists()
    {
        // 列表
        $page = input('get.page/d') ?: 1;
        $limit = input('get.limit/d') ?: 10;
        $module = input('get.module');
        $dataList = ConfigModel::where('profile', '=', 'prod')
            // ->where('module', '=', $module)
            ->page($page, $limit)
            ->orderBt('id')
            ->get();
        $total = ConfigModel::where('profile', '=', 'prod')
            ->count();


        // 构造动态页面数据
        $xyBuilderList = new \uiadmin\core\util\xybuilder\XyBuilderList();
        $listData = $xyBuilderList->init()
            ->addTopButton('add', '添加', [
                'api' => '/v1/admin/config/config/add',
                'apiParams' => '/' . $module
            ])
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/config/config/edit', 'title' => '修改配置信息'])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/config/config/delete',
                'title' => '确认要删除该配置吗？',
                'modalType' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p>删除配置不可恢复</p>',
            ])
            ->addColumn('id' , 'ID', ['width' => '50px'])
            ->addColumn('application', '应用', ['width' => '80px'])
            ->addColumn('label', '分支', ['width' => '70px'])
            ->addColumn('profile', '环境', ['width' => '70px'])
            ->addColumn('name', '名称', ['width' => '280px'])
            ->addColumn('title', '标题', ['width' => '150px'])
            ->addColumn('type', '配置类型', ['width' => '80px'])
            //->addColumn('placeholder', 'placeholder', ['width' => '150px'])
            //->addColumn('tip', '说明', ['width' => '200px'])
            //->addColumn('isSystem', '系统', ['width' => '50px'])
            //->addColumn('isDev', '开发者', ['width' => '80px'])
            //->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('status' , '状态', [
                'width' => '80px',
                'type' => 'template',
                'template' => 'switch',
                'options' => [ 1 => '正常', 0 => '禁用']
            ])
            ->addColumn('rightButtonList', '操作', [
                'minWidth' => '150px',
                'type' => 'template',
                'template' => 'rightButtonList'
            ])
            ->setTableName('xy_config')
            ->setDataList($dataList)
            ->setDataPage($total, $limit, $page)
            ->getData();

        // 返回数据
        return json([
            'code' => 200, 'msg' => '成功', 'data' => [
                'listData' => $listData
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
            // 数据验证
            $this->validateMake([
                'name' => 'require',
                'title' => 'require',
                'type' => 'require'
            ],
            [
                'name.require' => '配置名称必须',
                'title.require' => '配置标题必须',
                'type.require' => '配置类型必须'
            ]);
            $data = input('post.');
            $this->validate($data);

            $module = input('get.module');

            // 数据构造
            $dataDb = $data;
            if (count($dataDb) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            // $dataDb['defaultValue'] = isset($dataDb['value']) ? $dataDb['value'] : '';
            $dataDb['createTime'] = date("Y-m-d H:i:s");
            $dataDb['updateTime'] = date("Y-m-d H:i:s");
            $dataDb['status']   = 1;

            // 存储数据
            $ret = ConfigModel::create($dataDb);
            if ($ret->id) {
                return json(['code' => 200, 'msg' => '添加成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '添加失败', 'data' => []]);
            }
        } else {
            // 获取模块列表
            // $moduleList = coreModuleModel::where('status', 1)
            //     ->order('sortnum asc')
            //     ->select()->toArray();
            // $moduleListSelect = [];
            // foreach ($moduleList as $key => $val) {
            //     $moduleListSelect[$key]['title'] = $val['title'];
            //     $moduleListSelect[$key]['value'] = $val['name'];
            // }

            // 构造动态页面数据
            $xyBuilderForm = new \uiadmin\core\util\xybuilder\XyBuilderForm();
            $xyBuilderForm->init()
                ->setFormMethod('post')
                // ->addFormItem('module', '模块', 'select', $module, [
                //     'placeholder' => '请选择模块',
                //     'tip' => '模块是一个可分享使用的最小功能包',
                //     'options' => $moduleListSelect
                // ])
                ;
                $formData = $xyBuilderForm->addFormItem('application', '应用', 'text', 'uiadmin', [
                    'disabled' => true,
                    'placeholder' => '请输入英文',
                    'tip' => '预留字段，暂时保持默认'
                ])
                ->addFormItem('label', '配置分支', 'text', 'main', [
                    'disabled' => true,
                    'placeholder' => '默认值',
                    'tip' => '预留字段，暂时保持默认'
                ])
                ->addFormItem('profile', '配置环境', 'text', 'prod', [
                    'disabled' => true,
                    'placeholder' => '默认值',
                    'tip' => '预留字段，暂时保持默认'
                ])
                ->addFormItem('name', '配置名称', 'text', '', [
                    'placeholder' => '请输入英文',
                    'tip' => '配置名称一般类似uiadmin.site.title格式'
                ])
                ->addFormItem('title', '配置标题', 'text', '', [
                    'placeholder' => '请输入配置标题',
                    'tip' => '配置标题'
                ])
                ->addFormItem('type', '配置类型', 'select', '', [
                    'placeholder' => '请选择表单类型',
                    'tip' => '表单类型',
                    'options' => $xyBuilderForm::$formType
                ])
                ->addFormItem('value', '当前值', 'textarea', '', [
                    'placeholder' => '请输入配置当前值',
                    'tip' => '默认值'
                ])
                // ->addFormItem('default', '初始值', 'textarea', '', [
                //     'placeholder' => '请输入配置初始值',
                //     'tip' => '初始值'
                // ])
                ->addFormItem('placeholder', 'placeholder', 'text', '', [
                    'placeholder' => '请输入配置placeholder',
                    'tip' => '请输入配置placeholder'
                ])
                ->addFormItem('tip', '说明', 'text', '', [
                    'placeholder' => '请输入配置说明',
                    'tip' => '请输入配置说明'
                ])
                ->addFormItem('options', '选项', 'textarea', '', [
                    'placeholder' => '请输入配置额外项目json格式',
                    'tip' => '请输入配置额外项目'
                ])
                // ->addFormItem('isSystem', '是否系统', 'radio', 1, [
                //     'placeholder' => '请选择是否系统配置',
                //     'tip' => '系统配置一般不允许删除等风险操作',
                //     'options' => ['1' => '是', '0' => '否']
                // ])
                // ->addFormItem('isDev', '是否开发者', 'radio', 1, [
                //     'placeholder' => '请选择是否开发者配置',
                //     'tip' => '开发者意味着主要是给开发者编辑的配置',
                //     'options' => ['1' => '是', '0' => '否']
                // ])
                // ->addFormItem('sortnum', '排序', 'text', '', [
                //     'placeholder' => '请输入排序',
                //     'tip' => '请输入排序'
                // ])
                ->addFormRule('name', [
                    ['required' => true, 'message' => '请填写配置名称', 'trigger' => 'change'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写配置标题', 'trigger' => 'change'],
                ])
                ->addFormRule('type', [
                    ['required' => true, 'type' => 'string', 'message' => '请选择配置类型', 'trigger' => 'change'],
                ])
                ->setFormValues()
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
     * 修改
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function edit($id)
    {

        // 信息
        $info = ConfigModel::where('id', $id)
            ->first();
        if (request()->isPut()) {
            $this->validateMake([
                'name' => 'require',
                'title' => 'require',
                'type' => 'require'
            ],
            [
                'name.require' => '配置名称必须',
                'title.require' => '配置标题必须',
                'type.require' => '配置类型必须'
            ]);
            $data = input('post.');
            $this->validate($data);

            // 数据构造
            $dataDb = $data;
            if (count($dataDb) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
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
                return $this->return(['code' => 200, 'msg' => '修改成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '修改失败', 'data' => []]);
            }
        } else {
            // 获取模块列表
            // $moduleList = coreModuleModel::where('status', 1)
            //     ->order('sortnum asc')
            //     ->select()->toArray();
            // $moduleListSelect = [];
            // foreach ($moduleList as $key => $val) {
            //     $moduleListSelect[$key]['title'] = $val['title'];
            //     $moduleListSelect[$key]['value'] = $val['name'];
            // }

            // 构造动态页面数据
            $xyBuilderForm = new \uiadmin\core\util\xybuilder\XyBuilderForm();
            $xyBuilderForm->init()
                ->setFormMethod('put')
                // ->addFormItem('module', '模块', 'select', '', [
                //     'placeholder' => '请选择模块',
                //     'tip' => '模块是一个可分享使用的最小功能包',
                //     'options' => $moduleListSelect
                // ])
                ;
                $formData = $xyBuilderForm->addFormItem('application', '应用', 'text', 'uiadmin', [
                    'disabled' => true,
                    'placeholder' => '请输入英文',
                    'tip' => '预留字段，暂时保持默认'
                ])
                ->addFormItem('label', '配置分支', 'text', 'main', [
                    'disabled' => true,
                    'placeholder' => '默认值',
                    'tip' => '预留字段，暂时保持默认'
                ])
                ->addFormItem('profile', '配置环境', 'text', 'prod', [
                    'disabled' => true,
                    'placeholder' => '默认值',
                    'tip' => '预留字段，暂时保持默认'
                ])
                ->addFormItem('name', '配置名称', 'text', '', [
                    'placeholder' => '请输入英文',
                    'tip' => '配置名称一般类似uiadmin.site.title格式'
                ])
                ->addFormItem('title', '配置标题', 'text', '', [
                    'placeholder' => '请输入配置标题',
                    'tip' => '配置标题'
                ])
                ->addFormItem('type', '配置类型', 'select', '', [
                    'placeholder' => '请选择表单类型',
                    'tip' => '表单类型',
                    'options' => $xyBuilderForm::$formType
                ])
                ->addFormItem('value', '当前值', 'textarea', '', [
                    'placeholder' => '请输入配置当前值',
                    'tip' => '默认值'
                ])
                // ->addFormItem('default', '初始值', 'textarea', '', [
                //     'placeholder' => '请输入配置初始值',
                //     'tip' => '初始值'
                // ])
                ->addFormItem('placeholder', 'placeholder', 'text', '', [
                    'placeholder' => '请输入配置placeholder',
                    'tip' => '请输入配置placeholder'
                ])
                ->addFormItem('tip', '说明', 'text', '', [
                    'placeholder' => '请输入配置说明',
                    'tip' => '请输入配置说明'
                ])
                ->addFormItem('options', '选项', 'textarea', '', [
                    'placeholder' => '请输入配置额外项目json格式',
                    'tip' => '请输入配置额外项目'
                ])
                // ->addFormItem('isSystem', '是否系统', 'radio', 1, [
                //     'placeholder' => '请选择是否系统配置',
                //     'tip' => '系统配置一般不允许删除等风险操作',
                //     'options' => ['1' => '是', '0' => '否']
                // ])
                // ->addFormItem('isDev', '是否开发者', 'radio', 1, [
                //     'placeholder' => '请选择是否开发者配置',
                //     'tip' => '开发者意味着主要是给开发者编辑的配置',
                //     'options' => ['1' => '是', '0' => '否']
                // ])
                // ->addFormItem('sortnum', '排序', 'text', '', [
                //     'placeholder' => '请输入排序',
                //     'tip' => '请输入排序'
                // ])
                ->addFormRule('name', [
                    ['required' => true, 'message' => '请填写配置名称', 'trigger' => 'change'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写配置标题', 'trigger' => 'change'],
                ])
                ->addFormRule('type', [
                    ['required' => true, 'type' => 'string', 'message' => '请选择配置类型', 'trigger' => 'change'],
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
     * 删除
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function delete($id)
    {
        $info = ConfigModel::where(['id' => $id])
            ->first();
        if (isset($info->isSystem) && $info->isSystem) {
            return $this->return(['code' => 0, 'msg' => '系统级别不允许删除', 'data' => []]);
        }

        $ret = $info->delete(true);
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return $this->return(['code' => 0, 'msg' => '删除错误', 'data' => []]);
        }
    }
}
