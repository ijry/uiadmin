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

namespace app\core\util\iadypage;

/**
 * 动态表单
 * @author jry <ijry@qq.com>
 */
class IaDyform {

    // 数据
    private $data;
    public $form_type = [
        'text' => '单行文本',
        'textarea' => '多行文本',
        'array' => '自定义数组',
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
        'checkboxtree' => '树桩表格复选',
    ];

    /**
     * 初始化
     * @author jry <ijry@qq.com>
     */
    public function init() {
        $this->data = [
            'form_method' => 'post',
            'form_items' => [],
            'form_values' => [],
            'form_rules' =>[]
        ];
        return $this;
    }

    /**
     * 设置提交方法
     * @author jry <ijry@qq.com>
     */
    public function setFormMethod($method = 'post') {
        $this->data['form_method'] = $method;
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
        $item['name'] = $name;
        $item['title'] = $title;
        $item['type'] = $type;
        $item['value'] = $value;
        if (isset($extra['placeholder'])) {
            $extra['placeholder'] = $extra['placeholder'];
        }
        if (isset($extra['tip'])) {
            $extra['tip'] = $extra['tip'];
        }
        if (isset($extra['options'])) {
            $options = [];
            foreach ($extra['options'] as $key => $val) {
                if (!is_array($val)) {
                    $tmp['title'] = $val;
                    $tmp['value'] = $key;
                    $options[] = $tmp;
                } else {
                    $options[] = $val;
                }
            }
            $extra['options'] = $options;
        }
        $item['extra'] = $extra;
        $this->data['form_items'][] = $item;
        return $this;
    }

    /**
     * 添加表单验证
     * @author jry <ijry@qq.com>
     */
    public function addFormRule($name, $rule){
        $this->data['form_rules'][$name] = $rule;
        return $this;
    }

    /**
     * 设置表单数据
     * @author jry <ijry@qq.com>
     */
    public function setFormValues($data = []) {
        foreach ($this->data['form_items'] as $key => &$val) {
            $this->data['form_values'][$val['name']] = '';
            if (isset($data[$val['name']])) {
                $val['value'] = $data[$val['name']];
            }
            if ($val['value']) {
                $this->data['form_values'][$val['name']] = $val['value'];
            }
        }
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