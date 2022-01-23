<?php
/**
 * +----------------------------------------------------------------------
 * | UiAdmin [ 渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <ijry@qq.com>
 * +----------------------------------------------------------------------
*/

namespace app\core\taglib;
use think\template\TagLib;

/**
 * 标签
 */
class Core extends TagLib {
    /**
     * 定义标签列表
     */
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'menu_list' => ['attr' => 'id', 'close' => 1],
    ];

    /**
     * 菜单列表
     */
    public function tagMenu_list($tag, $content)
    {
        $id = $tag['id'];
        $parse = '<?php ';
        $parse .= '$core_menu = new \app\core\model\Menu();';
        $parse .= '$_dataList = $core_menu->where(\'menuLayer\', \'=\', "admin")->order(\'sortnum asc\')->select()->toArray();';
        $parse .= '$tree      = new \app\core\util\Tree();';
        $parse .= '$__LIST__ = $tree->list2tree($_dataList, \'path\', \'pmenu\', \'children\', 0, false);';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $id . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

}