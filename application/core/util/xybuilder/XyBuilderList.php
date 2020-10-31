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

namespace app\core\util\xybuilder;

/**
 * XyBuilderList动态列表
 *
 * @author jry <ijry@qq.com>
 */
class XyBuilderList {

    // 数据
    private $data;

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
            'dataList' => [],
            'dataListParams' => [
                'expandKey' => 'title',
                'isFold' => true,
                'tableName' => '',
                'selectable' => true,
                'selectType' => 'checkbox'
            ],
            'topButtonList' => [],
            'rightButtonList' => [],
            'rightButtonListModal' => [],
            'columns' => [],
            'dataPage' => [
                'total' => 0,
                'limit' => 0,
                'page' => 0
            ],
            'filterItems' => [],
            'filterValues' => []
        ];
        return $this;
    }

    /**
     * 添加顶部按钮
     * @author jry <ijry@qq.com>
     */
    public function addTopButton($name, $title, $pageData = [], $style = []) {
        $btn['name'] = $name;
        $btn['title'] = $title;
        $btn['pageData'] = [
            'pageType' => isset($pageData['pageType']) ? $pageData['pageType'] : 'modal',
            'modalType' => isset($pageData['modalType']) ? $pageData['modalType'] : 'form',
            'formMethod' => isset($pageData['formMethod']) ? $pageData['formMethod'] : 'delete',
            'show' => false,
            'path' => isset($pageData['path']) ? $pageData['path'] : '',
            'api' => $pageData['api'],
            'apiBlank' => '',
            'apiSuffix' => isset($pageData['apiSuffix']) ? $pageData['apiSuffix'] : [],
            'apiParams' => isset($pageData['apiParams']) ? $pageData['apiParams'] : '',
            'querySuffix' => isset($pageData['querySuffix']) ? $pageData['querySuffix'] : [],  // 参数变量
            'queryParams' => isset($pageData['queryParams']) ? $pageData['queryParams'] : [],  // 参数实际值
            'title' => isset($pageData['title']) ? $pageData['title'] : $title,
            'content' => isset($pageData['content']) ? $pageData['content'] : '',
            'okText' => isset($pageData['okText']) ? $pageData['okText'] : '',
            'cancelText' => isset($pageData['cancelText']) ? $pageData['cancelText'] : '',
            'width' => isset($pageData['width']) ? $pageData['width'] : '800',
            'loading' => false,
            'draggable' => false,
            'scrollable' => true,
        ];
        if ($btn['pageData']['path'] == '') {
            $btn['pageData']['path'] = ltrim($btn['pageData']['api'], '/v1/admin');
        }
        $btn['style'] = [
            'type' => isset($style['type']) ? $style['type'] : 'default',
            'size' => isset($style['size']) ? $style['size'] : 'default',
            'shape' => isset($style['shape']) ? $style['shape'] : 'square',
            'icon' => isset($style['icon']) ? $style['icon'] : '',
        ];
        $this->data['topButtonList'][] = $btn;
        return $this;
    }

    /**
     * 添加右侧按钮
     * @author jry <ijry@qq.com>
     */
    public function addRightButton($name, $title, $pageData = [], $style = []) {
        $btn = $this->getRightButton($name, $title, $pageData, $style);
        $this->data['rightButtonList'][] = $btn;
        return $this;
    }

    /**
     * 构造右侧按钮
     * @author jry <ijry@qq.com>
     */
    public function getRightButton($name, $title, $pageData = [], $style = []) {
        $btn = [];
        $btn['name'] = $name;
        $btn['title'] = $title;
        $btn['pageData'] = [
            'pageType' => isset($pageData['pageType']) ? $pageData['pageType'] : 'modal',
            'modalType' => isset($pageData['modalType']) ? $pageData['modalType'] : 'form',
            'formMethod' => isset($pageData['formMethod']) ? $pageData['formMethod'] : 'delete',
            'show' => false,
            'path' => isset($pageData['path']) ? $pageData['path'] : '',
            'api' => $pageData['api'],
            'apiBlank' => '',
            'apiSuffix' => isset($pageData['apiSuffix']) ? $pageData['apiSuffix'] : ['id'],  // 参数变量
            'apiParams' => isset($pageData['apiParams']) ? $pageData['apiParams'] : '',  // 参数实际值
            'querySuffix' => isset($pageData['querySuffix']) ? $pageData['querySuffix'] : [],  // 参数变量
            'queryParams' => isset($pageData['queryParams']) ? $pageData['queryParams'] : [],  // 参数实际值
            'title' => isset($pageData['title']) ? $pageData['title'] : $title,
            'height' => isset($pageData['height']) ? $pageData['height'] : 'auto',
            'content' => isset($pageData['content']) ? $pageData['content'] : '',
            'okText' => isset($pageData['okText']) ? $pageData['okText'] : '',
            'cancelText' => isset($pageData['cancelText']) ? $pageData['cancelText'] : '',
            'width' => isset($pageData['width']) ? $pageData['width'] : '1000',
            'noRefresh' => isset($pageData['noRefresh']) ? $pageData['noRefresh'] : false,
            'loading' => false,
            'draggable' => false,
            'scrollable' => true,
        ];
        if ($btn['pageData']['path'] == '') {
            $btn['pageData']['path'] = ltrim($btn['pageData']['api'], '/v1');
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
    public function addColumn($name, $title, $data = []) {
        $column = [
            'name' => $name,
            'title' => $title,
            'extra' => [
                'width' => '100px',
                'minWidth' => '',
                'options' => []
            ]
        ];
        if (isset($data['type'])) {
            $data['type'] = $data['type'];
        }
        if (isset($data['template'])) {
            $data['type'] = $data['template'];
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
                $data['extra']['options'] = $options;
            }
        }
        $column['extra'] = $data;
        $this->data['columns'][] = $column;
        return $this;
    }

    /**
     * 设置列表数据
     * @author jry <ijry@qq.com>
     */
    public function setDataList($dataList) {
        $this->data['dataList'] = $dataList;
        return $this;
    }

    /**
     * 设置分页
     * @author jry <ijry@qq.com>
     */
    public function setDataPage($total, $limit = 10, $page = 1) {
        $this->data['dataPage'] = [
            'total' => $total,
            'limit' => $limit,
            'page' => $page
        ];
        return $this;
    }

    /**
     * 设置展开字段
     * @author jry <ijry@qq.com>
     */
    public function setExpandKey($expandKey) {
        $this->data['dataListParams']['expandKey'] = $expandKey;
        return $this;
    }

    /**
     * 设置默认展开
     * @author jry <ijry@qq.com>
     */
    public function setIsFold($isFold) {
        $this->data['dataListParams']['isFold'] = $isFold;
        return $this;
    }

    /**
     * 设置数据表名
     * @author jry <ijry@qq.com>
     */
    public function setTableName($tableName) {
        $this->data['dataListParams']['tableName'] = $tableName;
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
        $this->data['filterItems'][] = $item;
        return $this;
    }

    /**
     * 返回数据
     * @author jry <ijry@qq.com>
     */
    public function getData() {
        foreach ($this->data['filterItems'] as $key => &$val) {
            $this->data['filterValues'][$val['name']] = $val['value'];
            if (is_numeric($val['value']) && in_array($val['type'], ['radio', 'switch'])) {
                $this->data['filterValues'][$val['name']] = $val['value'] = (int)$this->data['filterValues'][$val['name']];
            }
            if ($this->data['filterValues'][$val['name']] == '' && in_array($val['type'], ['checkbox', 'tags', 'images', 'files'])) {
                $this->data['filterValues'][$val['name']] = $val['value'] = [];
            }
        }

        // 处理每一行不同的右侧按钮
        foreach ($this->data['dataList'] as $key => &$value) {
            if (isset($value['rightButtonList'])) {
                $btns = [];
                foreach ($value['rightButtonList'] as $key1 => $value1) {
                    $btn = $this->getRightButton($value1['name'], $value1['title'], $value1['pageData'], $value1['style']);
                    $btns[] = $btn;
                    $exist = false;
                    foreach ($this->data['rightButtonListModal'] as $key2 => $value2) {
                        if ($value2['name'] == $value1['name']) {
                            $exist = true;
                        }
                    }
                    if (!$exist) {
                        $this->data['rightButtonListModal'][] = $btn;
                    }
                }
                $value['rightButtonList'] = $btns;
                unset($btns);
            }
        }
        return $this->data;
    }
}
