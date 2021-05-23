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
 * XyBuilderTab动态Tab
 *
 * @author jry <ijry@qq.com>
 */
class XyBuilderTab {

    // 数据
    private $data;

    /**
     * 初始化
     * @author jry <ijry@qq.com>
     */
    public function init() {
        $this->data = [
            'tabList' => [],
            'tabType' => 'card'
        ];
        return $this;
    }

    /**
     * 添加一个Tab
     * @author jry <ijry@qq.com>
     */
    public function addTab(
        $title,
        $list
    ) {
        foreach ($list as $key => &$tab) {
            if (!isset($tab['predata'])) {
                $tab['predata'] = '';
            }
            $pageData = [];
            $pageData['count']       = isset($tab['count']) ? $tab['count'] : 0;
            $pageData['modalType']  = isset($tab['pageData']['modalType']) ? $tab['pageData']['modalType'] : 'list';
            $pageData['apiBlank']   = isset($tab['pageData']['apiBlank']) ? $tab['pageData']['apiBlank'] : '';
            $pageData['api']         = isset($tab['pageData']['api']) ? $tab['pageData']['api'] : '';
            $pageData['show']        = isset($tab['pageData']['show']) ? $tab['pageData']['show'] : false;
            $pageData['apiParams']  = isset($tab['pageData']['apiParams']) ? $tab['pageData']['apiParams'] : '';
            $pageData['queryParams']  = isset($tab['pageData']['queryParams']) ? $tab['pageData']['queryParams'] : '';
            $tab['pageData']         = $pageData;
            unset($pageData);
        }
        $this->data['tabList'][] = [
            'title' => $title,
            'list' => $list
        ];
        return $this;
    }

    /**
     * 批量添加
     * @author jry <ijry@qq.com>
     */
    public function addTabs(
        $tabs
    ) {
        foreach ($tabs as $key => $value) {
            $this->addTab($value['title'], $value['list']);
        }
        return $this;
    }

    /**
     * 设置Tab样式
     * @author jry <ijry@qq.com>
     */
    public function setType($type = 'line') {
        $this->data['tabType'] = $type;
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
