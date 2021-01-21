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
            'filterValues' => [],
            'countList' => [],
            'config' => [
                'listExpandAll' => false,
                'modalDefaultWidth' => '1000px',
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
    public function addTopButton($name, $title, $pageData = [], $style = []) {
        $btn['name'] = $name;
        $btn['title'] = $title;
        $btn['pageData'] = [
            'show' => false,
            'pageType' => 'modal', // 支持modal和page
            'modalType' => 'form',
            'modalClosable' => false,
            'width'         => '1000px',
            'height'        => '60vh'
        ];
        $btn['pageData'] = array_merge($btn['pageData'], $pageData);
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
            'pageType' => 'modal', // 支持modal和page
            'modalType' => 'form',
            'modalClosable' => false,
            'width'         => '1000px',
            'height'        => '60vh',
        ];
        $btn['pageData'] = array_merge($btn['pageData'], $pageData);
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
     * 返回数据
     * @author jry <ijry@qq.com>
     */
    public function getData() {
        return $this->data;
    }
}
