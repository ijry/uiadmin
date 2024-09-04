<?php
/**
 * +----------------------------------------------------------------------
 * | uiadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/

namespace uiadmin\core\util\xybuilder;

/**
 * XYBuilderInfo动态信息
 *
 * @author jry <ijry@qq.com>
 */
class XyBuilderInfo {

    // 数据
    private $data;

    /**
     * 初始化
     * @author jry <ijry@qq.com>
     */
    public function init() {
        $this->data = [
            'infoList' => []
        ];
        return $this;
    }

    /**
     * 添加组
     * @author jry <ijry@qq.com>
     */
    public function addInfoGroup(
        $group
    ) {
        $this->data['infoList'][] = $group;
        return $this;
    }

    /**
     * 返回数据
     * @author jry <ijry@qq.com>
     */
    public function getData() {
        foreach ($this->data['infoList'] as $key => &$value) {
            foreach ($value['data'] as $key1 => &$value1) {
                if (!isset($value1['extra'])) {
                    $value1['extra'] = [];
                }
                if (!isset($value1['extra']['sm'])) {
                    $value1['extra']['sm'] = [];
                }
            }
        }
        return $this->data;
    }
}
