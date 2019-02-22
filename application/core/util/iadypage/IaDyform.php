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
        $placeholder = '',
        $tip = '',
        $extra = []
    ) {
        $item['name'] = $name;
        $item['title'] = $title;
        $item['type'] = $type;
        $item['value'] = $value;
        $item['placeholder'] = $placeholder;
        $item['tip'] = $tip;
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