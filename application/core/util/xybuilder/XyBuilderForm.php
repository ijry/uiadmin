<?php
/**
 * +----------------------------------------------------------------------
 * | UiAdmin [ 渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <ijry@qq.com>
 * +----------------------------------------------------------------------
*/

namespace app\core\util\xybuilder;

/**
 * XyBuilderForm动态表单
 *
 * @author jry <ijry@qq.com>
 */
class XyBuilderForm {

    // 数据
    private $data;
    public $form_type = [
        'hidden' => '隐藏元素',
        'text' => '单行文本',
        'password' => '密码',
        'url' => 'URL网址',
        'email' => '邮箱',
        'date' => '日期',
        'number' => '数字',
        'digit' => '浮点型数字',
        'tel' => '手机号',
        'textarea' => '多行文本',
        'array' => '自定义数组',
        'markdown' => 'Markdown',
        'tinymce' => 'TinyMCE富文本',
        'select' => '下拉框',
        'radio' => '单选框',
        'checkbox' => '多选框',
        'switch' => '开关',
        'slider' => '滑块',
        'datepicker' => '日期',
        'timepicker' => '时刻',
        'datetimepicker' => '时间',
        'rate' => '星级评分',
        'cascader' => '级联选择',
        'colorpicker' => '颜色选择器',
        'image' => '单图上传',
        'images' => '多图上传',
        'file' => '单文件上传',
        'files' => '多文件上传',
        'checkboxtree' => '树状表格复选',
    ];

    /**
     * 初始化
     * @author jry <ijry@qq.com>
     */
    public function init() {
        $this->data = [
            'alertList' => [
                'top' => [],
                'bottom' => []
            ],
            'formMethod' => 'post',
            'formItems' => [],
            'itemValues' => [], // 原值
            'formValues' => [], // 构造值（1.0.0后由前端实现不再需要由后端构造）
            'formRules' => [],
            'formTabs' => [],
            'config' => [
                'continue' => false, // 显示继续添加
                'itemDefaultPosition' => '',
                'submitButtonTitle' => '确认',
                'cancelButtonTitle' => '取消',
                'footerButtonLength' => '120px',
                'labelPosition' => 'left',
                'labelWidth' => '100px',
                'defaultUploadDriver' => '',
                'defaultUploadAction' => request()->root(true) . '/api/v1/core/index/upload/',
                'defaultUploadMaxSize' => 512
            ]
        ];
        return $this;
    }

    /**
     * 设置配置
     * @author jry <ijry@qq.com>
     */
    public function setConfig($name, $value) {
        $this->data['config'][$name] = $value;
        return $this;
    }

    /**
     * 设置提交方法
     * @author jry <ijry@qq.com>
     */
    public function setFormMethod($method = 'post') {
        $this->data['formMethod'] = $method;
        return $this;
    }

    /**
     * 添加表达项目
     * @author jry <ijry@qq.com>
     */
    public function addFormItem(
        $name,
        $title,
        $type = 'text',
        $value = '' ,
        $extra = []
    ) {
        $item = [];
        $item['name'] = $name;
        $item['title'] = $title;
        $item['type'] = $type;
        $item['value'] = $value;
        $item['extra'] = $extra;
        $this->data['formItems'][] = $item;
        return $this;
    }

    /**
     * 添加表单验证
     * @author jry <ijry@qq.com>
     */
    public function addFormRule($name, $rule){
        $this->data['formRules'][$name] = $rule;
        return $this;
    }

    /**
     * 设置表单数据
     * @author jry <ijry@qq.com>
     */
    public function setFormValues($data = []) {
        $this->data['itemValues'] = $data;
        return $this;
    }

    /**
     * 返回数据
     * @author jry <ijry@qq.com>
     */
    public function getData() {
        return $this->data;
    }
}
