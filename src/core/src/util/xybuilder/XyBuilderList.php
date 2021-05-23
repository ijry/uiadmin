<?php
/**
 * +----------------------------------------------------------------------
 * | uniadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2021 http://uniadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/

namespace uniadmin\core\xybuilder;

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
                'tableName' => '',
                'selectable' => true,
                'selectType' => 'checkbox'
            ],
            'topButtonList' => [],
            'rightButtonList' => [],
            'columns' => [],
            'dataPage' => [
                'total' => 0,
                'limit' => 0,
                'page' => 0
            ],
            'filterItems' => [],
            'filterValues' => [],
            'filterExtra' => [],
            'countList' => [],
            'config' => [
                'listExpandAll' => false,
                'modalDefaultWidth' => '800px',
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
     * 添加顶部按钮
     * @author jry <ijry@qq.com>
     */
    public function addAlertItem($layer, $item) {
        $this->data['alertList'][$layer][] = $item;
    }

    /**
     * 添加顶部按钮
     * @author jry <ijry@qq.com>
     */
    public function addTopButton($name, $title, $pageData = [], $style = []) {
        $btn['name'] = $name;
        $btn['title'] = $title;
        $btn['pageData'] = [
            'show' => false,
            'pageType' => 'modal', // 支持modal和page
            'modalType' => 'form',
            'modalClosable' => false,
        ];
        $btn['pageData'] = array_merge($btn['pageData'], $pageData);
        // if ($btn['pageData']['path'] && $btn['pageData']['path'] == '') {
        //     $btn['pageData']['path'] = ltrim($btn['pageData']['api'], '/v1');
        // }
        $btn['style'] = $style;
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
            'show' => false,
            'pageType' => 'page', // 支持modal和page
            'modalType' => 'form',
            'modalClosable' => false,
        ];
        $btn['pageData'] = array_merge($btn['pageData'], $pageData);
        // 暂未启用，本打算在pageType=page模式下将URL优化减去v1/admin等前缀的，
        // 因为之前按钮定义的api字段都会直接带有/v1/api/admin，后来因为iadmin、
        // eadmin等前缀太多没启用此规范（定义按钮时没有将api拆成跟接口一样的apiPrefix、
        // menuLayer、path三个变量主要是考虑到通用性，可以请求非本系统的接口）
        // if ($btn['pageData']['path'] && $btn['pageData']['path'] == '') {
        //     $btn['pageData']['path'] = ltrim($btn['pageData']['api'], '/v1');
        // }
        $btn['style'] = $style;
        return $btn;
    }

    /**
     * 批量添加顶部按钮
     * @author jry <ijry@qq.com>
     */
    public function addTopButtons($buttons){
        foreach ($buttons as $key => $value) {
            $this->addTopButton($value['name'], $value['title'], $value['pageData'], $value['style']);
        }
        return $this;
    }

    /**
     * 批量添加右侧按钮
     * @author jry <ijry@qq.com>
     */
    public function addRightButtons($buttons){
        foreach ($buttons as $key => $value) {
            $this->addRightButton($value['name'], $value['title'], $value['pageData'], $value['style']);
        }
        return $this;
    }

    /**
     * 批量添加表格列
     * @author jry <ijry@qq.com>
     */
    public function addColums($columns){
        foreach ($columns as $key => $value) {
            $this->addColumn($value['key'], $value['title'], $value['data']);
        }
        return $this;
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
                'type' => '',
                'width' => '',
                'minWidth' => '',
                'show' => true,
                'loading' => false,
                'options' => [],
                'extend' => []
            ]
        ];
        if (isset($data['template'])) {
            $data['type'] = $data['template'];
            unset($data['template']);
        }
        $column['extra'] = array_merge($column['extra'], $data);
        $this->data['columns'][] = $column;
        return $this;
    }

    /**
     * 设置列表数据
     * @author jry <ijry@qq.com>
     */
    public function setDataList($dataList) {
        if (is_object($dataList)) {
            $dataList = $dataList->toArray();
        }
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
     * 设置数据表名
     * @author jry <ijry@qq.com>
     */
    public function setTableName($tableName) {
        $this->data['dataListParams']['tableName'] = $tableName;
        return $this;
    }

    /**
     * 设置选择
     * @author jry <ijry@qq.com>
     */
    public function setSelectType($selectType = 'checkbox') {
        if ($selectType) {
            $this->data['dataListParams']['selectable'] = true;
            $this->data['dataListParams']['selectType'] = $selectType;
        } else {
            $this->data['dataListParams']['selectable'] = false;
        }
        return $this;
    }

    /**
     * 批量添加搜索
     * @author jry <ijry@qq.com>
     */
    public function addFilterItems($list) {
        foreach ($list as $k => $v) {
            if (count($v) > 1) {
                $this->addFilterItem($v[0], $v[1], $v[2], $v[3], $v[4]);
            }
        }
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
        $item['extra'] = $extra;
        $this->data['filterItems'][] = $item;
        return $this;
    }

    /**
     * 筛选功能其他项目
     * @author jry <ijry@qq.com>
     */
    public function setFilterExtra($item) {
        $this->data['filterExtra'] = $item;
        return $this;
    }

    /**
     * 添加一个统计
     * @author jry <ijry@qq.com>
     */
    public function addCount(
        $item = ['title' => '', 'icon' => 'xyicon xyicon-my', 'bgColor' => '#f8f8f8'],
        $current = ['value' => 0, 'suffix' => ''],
        $content = ['value' => '', 'list' => []]
    ) {
        $this->data['countList'][] = [
            'item' => $item,
            'current' => $current,
            'content' => $content
        ];;
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