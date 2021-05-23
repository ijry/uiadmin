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
 * XyBuilder随意组合
 *
 * @author jry <ijry@qq.com>
 */
class XyBuilderStack {

    // 数据
    private $data;

    /**
     * 初始化
     * $stack = [
     *     'type' => 'form',  // form,list,info,echarts,tab
     *     'span' => 24,
     *     'data' => [],
     * ]
     * @author jry <ijry@qq.com>
     */
    public function init() {
        $this->data = [
            'stackList' => []
        ];
        return $this;
    }

    /**
     * 添加组
     * @author jry <ijry@qq.com>
     */
    public function add(
        $title,
        $pageData,
        $predata = [],
        $span = 24
    ) {
        $this->data['stackList'][] = [
            'title' => $title,
            'pageData' => $pageData,
            'predata' => $predata,
            'span' => 24
        ];
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
