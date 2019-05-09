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

namespace app\cms\taglib;
use think\template\TagLib;

/**
 * 标签
 */
class Cms extends TagLib{
    /**
     * 定义标签列表
     */
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'cate_list' => ['attr' => 'id,cate_id', 'close' => 1],
    ];

    /**
     * 分类列表
     */
    public function tagCate_list($tag, $content)
    {
        $id = $tag['id'];
        $cate_id = isset($tag['cate_id']) ? $tag['cate_id'] : 0;
        $parse = '<?php ';
        $parse .= '$cms_cate = new \app\cms\model\Cate();';
        $parse .= '$_data_list = $cms_cate->select()->toArray();';
        $parse .= '$tree      = new \app\core\util\Tree();';
        $parse .= '$__LIST__ = $tree->list2tree($_data_list);';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $id . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

}