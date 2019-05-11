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
            'data_list' => [],
            'top_button_list' => [],
            'right_button_list' => [],
            'columns' => [],
            'data_page' => [
                'total_count' => 0,
                'limit' => 0,
                'page' => 0
            ],
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
        $btn['title'] = $title;
        $btn['page_data'] = [
            'page_type' => isset($page_data['page_type']) ? $page_data['page_type'] : 'modal',
            'modal_type' => isset($page_data['modal_type']) ? $page_data['modal_type'] : 'form',
            'form_method' => isset($page_data['form_method']) ? $page_data['form_method'] : 'delete',
            'show' => false,
            'path' => isset($page_data['path']) ? $page_data['path'] : '',
            'api' => $page_data['api'],
            'api_blank' => '',
            'api_suffix' => isset($page_data['api_suffix']) ? $page_data['api_suffix'] : ['id'],
            'api_params' => isset($page_data['api_params']) ? $page_data['api_params'] : '',
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
        $this->data['right_button_list'][$name] = $btn;
        return $this;
    }

    /**
     * 添加表格列
     * @author jry <ijry@qq.com>
     */
    public function addColumn($key, $title, $data = []) {
        $column = [
            'key' => $key,
            'title' => $title,
            'width' => '100px'
        ];
        if (isset($data['width'])) {
            $column['width'] = $data['width'];
        }
        if (isset($data['type'])) {
            $column['type'] = $data['type'];
        }
        if (isset($data['template'])) {
            $column['template'] = $data['template'];
            if ($data['template'] == 'right_button_list') {
                $column['width'] = 60 * count($this->data['right_button_list']) . 'px';
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
     * 返回数据
     * @author jry <ijry@qq.com>
     */
    public function getData() {
        return $this->data;
    }
}
