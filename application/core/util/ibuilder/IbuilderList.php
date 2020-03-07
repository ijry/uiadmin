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

namespace app\core\util\ibuilder;

/**
 * IbuilderList动态列表
 *
 * @author jry <ijry@qq.com>
 */
class IbuilderList {

    // 数据
    private $data;

    /**
     * 初始化
     * @author jry <ijry@qq.com>
     */
    public function init() {
        $this->data = [
            'alert_list' => [
                'top' => [],
                'bottom' => []
            ],
            'data_list' => [],
            'data_list_params' => [
                'expand_key' => 'title',
                'is_fold' => true,
                'table_name' => ''
            ],
            'top_button_list' => [],
            'right_button_list' => [],
            'right_button_list_modal' => [],
            'columns' => [],
            'data_page' => [
                'total_count' => 0,
                'limit' => 0,
                'page' => 0
            ],
            'filter_items' => [],
            'filter_values' => [],
            'right_button_length' => 0
        ];
        return $this;
    }

    /**
     * 添加顶部按钮
     * @author jry <ijry@qq.com>
     */
    public function addTopButton($name, $title, $page_data = [], $style = []) {
        $btn['title'] = $title;
        $btn['page_data'] = [
            'page_type' => isset($page_data['page_type']) ? $page_data['page_type'] : 'modal',
            'modal_type' => isset($page_data['modal_type']) ? $page_data['modal_type'] : 'form',
            'form_method' => isset($page_data['form_method']) ? $page_data['form_method'] : 'delete',
            'show' => false,
            'path' => isset($page_data['path']) ? $page_data['path'] : '',
            'api' => $page_data['api'],
            'api_blank' => '',
            'api_suffix' => isset($page_data['api_suffix']) ? $page_data['api_suffix'] : [],
            'api_params' => isset($page_data['api_params']) ? $page_data['api_params'] : '',
            'query_suffix' => isset($page_data['query_suffix']) ? $page_data['query_suffix'] : [],  // 参数变量
            'query_params' => isset($page_data['query_params']) ? $page_data['query_params'] : [],  // 参数实际值
            'title' => isset($page_data['title']) ? $page_data['title'] : $title,
            'content' => isset($page_data['content']) ? $page_data['content'] : '',
            'okText' => isset($page_data['okText']) ? $page_data['okText'] : '',
            'cancelText' => isset($page_data['cancelText']) ? $page_data['cancelText'] : '',
            'width' => isset($page_data['width']) ? $page_data['width'] : '800',
            'loading' => false,
            'draggable' => false,
            'scrollable' => true,
        ];
        if ($btn['page_data']['path'] == '') {
            $btn['page_data']['path'] = ltrim($btn['page_data']['api'], '/v1/admin');
        }
        $btn['style'] = [
            'type' => isset($style['type']) ? $style['type'] : 'default',
            'size' => isset($style['size']) ? $style['size'] : 'default',
            'shape' => isset($style['shape']) ? $style['shape'] : 'square',
            'icon' => isset($style['icon']) ? $style['icon'] : '',
        ];
        $this->data['top_button_list'][$name] = $btn;
        return $this;
    }

    /**
     * 添加右侧按钮
     * @author jry <ijry@qq.com>
     */
    public function addRightButton($name, $title, $page_data = [], $style = []) {
        $btn = $this->getRightButton($name, $title, $page_data, $style);
        $this->data['right_button_length'] += 18 * mb_strwidth($btn['title']);
        $this->data['right_button_list'][$name] = $btn;
        return $this;
    }

    /**
     * 构造右侧按钮
     * @author jry <ijry@qq.com>
     */
    public function getRightButton($name, $title, $page_data = [], $style = []) {
        $btn = [];
        $btn['title'] = $title;
        $btn['page_data'] = [
            'page_type' => isset($page_data['page_type']) ? $page_data['page_type'] : 'modal',
            'modal_type' => isset($page_data['modal_type']) ? $page_data['modal_type'] : 'form',
            'form_method' => isset($page_data['form_method']) ? $page_data['form_method'] : 'delete',
            'show' => false,
            'path' => isset($page_data['path']) ? $page_data['path'] : '',
            'api' => $page_data['api'],
            'api_blank' => '',
            'api_suffix' => isset($page_data['api_suffix']) ? $page_data['api_suffix'] : ['id'],  // 参数变量
            'api_params' => isset($page_data['api_params']) ? $page_data['api_params'] : '',  // 参数实际值
            'query_suffix' => isset($page_data['query_suffix']) ? $page_data['query_suffix'] : [],  // 参数变量
            'query_params' => isset($page_data['query_params']) ? $page_data['query_params'] : [],  // 参数实际值
            'title' => isset($page_data['title']) ? $page_data['title'] : $title,
            'height' => isset($page_data['height']) ? $page_data['height'] : 'auto',
            'content' => isset($page_data['content']) ? $page_data['content'] : '',
            'okText' => isset($page_data['okText']) ? $page_data['okText'] : '',
            'cancelText' => isset($page_data['cancelText']) ? $page_data['cancelText'] : '',
            'width' => isset($page_data['width']) ? $page_data['width'] : '1000',
            'no_refresh' => isset($page_data['no_refresh']) ? $page_data['no_refresh'] : false,
            'loading' => false,
            'draggable' => false,
            'scrollable' => true,
        ];
        if ($btn['page_data']['path'] == '') {
            $btn['page_data']['path'] = ltrim($btn['page_data']['api'], '/v1');
        }
        $btn['style'] = [
            'type' => isset($style['type']) ? $style['type'] : 'default',
            'size' => isset($style['size']) ? $style['size'] : 'small',
            'shape' => isset($style['shape']) ? $style['shape'] : 'default',
            'icon' => isset($style['icon']) ? $style['icon'] : '',
        ];
        return $btn;
    }

    /**
     * 添加表格列
     * @author jry <ijry@qq.com>
     */
    public function addColumn($key, $title, $data = []) {
        $column = [
            'key' => $key,
            'title' => $title,
            'width' => '100px',
            'minWidth' => '',
            'extra' => [
                'options' => []
            ]
        ];
        if (isset($data['width'])) {
            $column['width'] = $data['width'];
        }
        if (isset($data['type'])) {
            $column['type'] = $data['type'];
        }
        if (isset($data['template'])) {
            $column['template'] = $column['slot'] = $data['template'];
            if ($data['template'] == 'right_button_list') {
                $column['width'] = '';
                if ($column['minWidth']) {
                    $column['width'] = (rtrim($column['minWidth'], 'px') + $this->data['right_button_length']) . 'px';
                }
            }
        }
        if (isset($data['options'])) {
            $options = [];
            if (is_array($data['options'])) {
                foreach ($data['options'] as $key => $val) {
                    if (!is_array($val)) {
                        $tmp['title'] = $val;
                        $tmp['value'] = $key;
                        $options[] = $tmp;
                    } else {
                        $options[$key] = $val;
                    }
                }
                $column['extra']['options'] = $options;
            }
        }
        $this->data['columns'][] = $column;
        return $this;
    }

    /**
     * 设置列表数据
     * @author jry <ijry@qq.com>
     */
    public function setDataList($data_list) {
        $this->data['data_list'] = $data_list;
        return $this;
    }

    /**
     * 设置分页
     * @author jry <ijry@qq.com>
     */
    public function setDataPage($total_count, $limit = 10, $page = 1) {
        $this->data['data_page'] = [
            'total_count' => $total_count,
            'limit' => $limit,
            'page' => $page
        ];
        return $this;
    }

    /**
     * 设置展开字段
     * @author jry <ijry@qq.com>
     */
    public function setExpandKey($expand_key) {
        $this->data['data_list_params']['expand_key'] = $expand_key;
        return $this;
    }

    /**
     * 设置默认展开
     * @author jry <ijry@qq.com>
     */
    public function setIsFold($is_fold) {
        $this->data['data_list_params']['is_fold'] = $is_fold;
        return $this;
    }

    /**
     * 设置数据表名
     * @author jry <ijry@qq.com>
     */
    public function setTableName($table_name) {
        $this->data['data_list_params']['table_name'] = $table_name;
        return $this;
    }

    /**
     * 添加搜索
     * @author jry <ijry@qq.com>
     */
    public function addFilterItem(
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
        $extra['placeholder'] = isset($extra['placeholder']) ? $extra['placeholder'] : '';
        $extra['tip'] = isset($extra['tip']) ? $extra['tip'] : '';
        $extra['position'] = isset($extra['position']) ? $extra['position'] : 'left';
        if (isset($extra['options']) && is_array($extra['options'])) {
            $options = [];
            if (is_array($extra['options'])) {
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
        }
        $item['extra'] = $extra;
        $this->data['filter_items'][] = $item;
        return $this;
    }

    /**
     * 返回数据
     * @author jry <ijry@qq.com>
     */
    public function getData() {
        foreach ($this->data['filter_items'] as $key => &$val) {
            $this->data['filter_values'][$val['name']] = $val['value'];
            if (is_numeric($val['value']) && in_array($val['type'], ['radio', 'switch'])) {
                $this->data['filter_values'][$val['name']] = $val['value'] = (int)$this->data['filter_values'][$val['name']];
            }
            if ($this->data['filter_values'][$val['name']] == '' && in_array($val['type'], ['checkbox', 'tags', 'images', 'files'])) {
                $this->data['filter_values'][$val['name']] = $val['value'] = [];
            }
        }

        // 处理每一行不同的右侧按钮
        foreach ($this->data['data_list'] as $key => &$value) {
            if (isset($value['right_button_list'])) {
                $btns = [];
                foreach ($value['right_button_list'] as $key1 => $value1) {
                    $btn = $this->getRightButton($value1['name'], $value1['title'], $value1['page_data'], $value1['style']);
                    $btns[$value1['name']] = $btn;
                    if (!isset($this->data['right_button_list_modal'][$value1['name']])) {
                        $this->data['right_button_list_modal'][$value1['name']] = $btn;
                    }
                }
                $value['right_button_list'] = $btns;
                unset($btns);
            }
        }
        return $this->data;
    }
}
