<?php
/**
 * +----------------------------------------------------------------------
 * | think-boot [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2020 http://jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
 * | 
*/

namespace uiadmin\core\util\xybuilder;

/**
 * XyBuilderForm动态表单
 *
 * @author jry <ijry@qq.com>
 */
class XyBuilderForm {

    // 数据
    private $data;
    public static $formType = [
        'tabs' => 'TABS切换',
        'hidden' => '隐藏元素',
        'static' => '静态文本',
        'link' => '跳转链接',
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
        'select' => '下拉框',
        'selects' => '下拉框多选',
        'radio' => '单选框',
        'checkbox' => '多选框',
        'switch' => '开关',
        'slider' => '滑块',
        'tags' => '标签',
        'datepicker' => '日期',
        'timepicker' => '时刻',
        'datetimepicker' => '时间',
        'daterangepicker' => '日期区间',
        'datetimerangepicker' => '时间区间',
        'rate' => '星级评分',
        'cascader' => '级联选择',
        'region' => '省市区联动',
        'colorpicker' => '颜色选择器',
        'image' => '单图上传',
        'imageflex' => '单图列表',
        'images' => '多图上传',
        'file' => '单文件上传',
        'files' => '多文件上传',
        'poster' => '分享海报',
        'selectlist' => '列表选择器',
        'checkboxtree' => '树状表格复选',
        'markdown' => 'Markdown',
        'html' => '富文本',
        'tinymce' => 'TinyMCE富文本',
        'sku' => '商品规格',
        'fee' => '运费模板'
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
            'formCols' => [], // 表单项目分栏
            'formGroups' => [], // 表单项目分组
            'formItems' => [],
            'formValues' => [],
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
                'defaultUploadDriver' => config('uiadmin.upload.defaultUploadDriver'),
                'defaultUploadAction' => scheme() . '://' . $_SERVER['HTTP_HOST'] . '/api/v1/core/upload/upload',
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
     * 添加顶部提醒
     * @author jry <ijry@qq.com>
     */
    public function addAlertItem($layer, $item) {
        $this->data['alertList'][$layer][] = $item;
        return $this;
    }

    /**
     * 设置tab
     * @author jry <ijry@qq.com>
     */
    public function addFormTab($tab) {
        $this->data['formTabs'][] = $tab;
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
     * 构造表单项
     * @author jry <ijry@qq.com>
     */
    public function getFormItem(
        $name,
        $title,
        $type = 'text',
        $value = '' ,
        $extra = []
    ) {
        $item['name'] = $name;
        $item['title'] = $title;
        $item['type'] = $type;
        $item['value'] = $value;
        $item['extra'] = $extra;
        return $item;
    }
    
    /**
     * 添加表单分组
     * @author jry <ijry@qq.com>
     */
    public function addFormCol(
        $name,
        $span = [],
        $itemList = [],
        $extra = []
    ) {
        $this->data['formCols'][] = [
            'name' => $name,
            'span' => $span,
            'itemList' => $itemList,
            'extra' => $extra
        ];
        return $this;
    }

    /**
     * 添加表单分组
     * @author jry <ijry@qq.com>
     */
    public function addFormGroup(
        $name,
        $title,
        $itemList = [],
        $extra = []
    ) {
        $this->data['formGroups'][] = [
            'name' => $name,
            'title' => $title,
            'itemList' => $itemList,
            'extra' => $extra
        ];
        return $this;
    }

    /**
     * 添加表单项目
     * @author jry <ijry@qq.com>
     */
    public function addFormItem(
        $name,
        $title,
        $type = 'text',
        $value = '' ,
        $extra = []
    ) {
        $item = $this->getFormItem($name, $title, $type, $value, $extra);
        if (count($this->data['formTabs']) > 0) {
            $this->data['formTabs'][count($this->data['formTabs']) - 1]['formItems'][] = $item;
            $this->data['formTabs'][count($this->data['formTabs']) - 1]['formRules']['default'] = [];
        } else {
            $this->data['formItems'][] = $item;
            // $this->data['formRules']['default'] = [];
        }
        return $this;
    }

    /**
     * 添加表单验证
     * @author jry <ijry@qq.com>
     */
    public function addFormRule($name, $rule){
        if (count($this->data['formTabs']) > 0) {
            $this->data['formTabs'][count($this->data['formTabs']) - 1]['formRules'][$name] = $rule;
        } else {
            $this->data['formRules'][$name] = $rule;
        }
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
