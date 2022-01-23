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

namespace app\core\util;

/**
 * 列表树生成工具类
 *
 * @author jry <ijry@qq.com>
 */
class Tree
{
    /**
     * 用于树型数组完成递归格式的全局变量
     * @author jry <ijry@qq.com>
     */
    private $formatTree;

    /**
     * 将格式数组转换为基于标题前缀的树（实际还是列表，只是通过在相应字段加前缀实现类似树状结构）
     * @param array $list
     * @param integer $level 进行递归时传递用的参数
     */
    private function _array2tree($list, $level = 0, $title = 'title')
    {
        foreach ($list as $key => $val) {
            $title_prefix = str_repeat("-", $level * 4);
            $title_prefix .= "┝ ";
            $val['level']        = $level;
            $val['title_prefix'] = $level == 0 ? '' : $title_prefix;
            $val['title_show']   = $level == 0 ? $val[$title] : $title_prefix . $val[$title];
            if (!array_key_exists('children', $val)) {
                array_push($this->formatTree, $val);
            } else {
                $child = $val['children'];
                unset($val['children']);
                array_push($this->formatTree, $val);
                $this->_array2tree($child, $level + 1, $title); //进行下一层递归
            }
        }
        return;
    }

    /**
     * 将格式数组(真正的Tree结构)转换为基于标题前缀的树
     * @param array $list
     * @param integer $level 进行递归时传递用的参数
     */
    public function array2tree($list, $title = 'title', $pk = 'id', $pid = 'pid', $root = 0, $strict = true)
    {
        $list             = $this->list2tree($list, $pk, $pid, 'children', $root, $strict);
        $this->formatTree = array();
        $this->_array2tree($list, 0, $title);
        return $this->formatTree;
    }

    /**
     * 将数据集转换成Tree（真正的Tree结构）
     * @param array $list 要转换的数据集
     * @param string $pk ID标记字段
     * @param string $pid parent标记字段
     * @param string $child 子代key名称
     * @param string $root 返回的根节点ID
     * @param string $strict 默认非严格模式
     * @return array
     */
    public function list2tree($list, $pk = 'id', $pid = 'pid', $child = 'children', $root = 0, $strict = false)
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parent_id = isset($data[$pid]) ? $data[$pid] : null;
                if ($parent_id === null || (String) $root === $parent_id) {
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parent_id])) {
                        $parent           = &$refer[$parent_id];
                        $parent[$child][] = &$list[$key];
                    } else {
                        if ($strict === false) {
                            $tree[] = &$list[$key];
                        }
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * 将list2tree的树还原成列表
     * @param    array $tree    原来的树
     * @param    string $child 孩子节点的键
     * @param    string $order 排序显示的键，一般是主键 升序排列
     * @param    array $list 过渡用的中间数组，
     * @return array 返回排过序的列表数组
     */
    public function tree2list($tree, $child = 'children', $order = 'id', &$list = array())
    {
        if (is_array($tree)) {
            foreach ($tree as $key => $value) {
                $reffer = $value;
                if (isset($reffer[$child])) {
                    unset($reffer[$child]);
                    $this->tree2list($value[$child], $child, $order, $list);
                }
                $list[] = $reffer;
            }
            $list = $this->listSortBy($list, $order, $sortby = 'asc');
        }
        return $list;
    }

    /**
     * 对查询结果集进行排序
     * @access public
     * @param array $list 查询结果
     * @param string $field 排序的字段名
     * @param array $sortby 排序类型 asc正向排序 desc逆向排序 nat自然排序
     * @return array
     */
    public function listSortBy($list, $field, $sortby = 'asc')
    {
        if (is_array($list)) {
            $refer = $resultSet = array();
            foreach ($list as $i => $data) {
                $refer[$i] = &$data[$field];
            }
            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc': // 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ($refer as $key => $val) {
                $resultSet[] = &$list[$key];
            }
            return $resultSet;
        }
        return false;
    }

    /**
     * 在数据列表中搜索
     * @access public
     * @param array $list 数据列表
     * @param mixed $condition 查询条件
     * 支持 array('name'=>$value) 或者 name=$value
     * @return array
     */
    public function listSearch($list, $condition)
    {
        if (is_string($condition)) {
            parse_str($condition, $condition);
        }
        // 返回的结果集合
        $resultSet = array();
        foreach ($list as $key => $data) {
            $find = false;
            foreach ($condition as $field => $value) {
                if (isset($data[$field])) {
                    if (0 === strpos($value, '/')) {
                        $find = preg_match($value, $data[$field]);
                    } else if ($data[$field] == $value) {
                        $find = true;
                    }
                }
            }
            if ($find) {
                $resultSet[] = &$list[$key];
            }
        }
        return $resultSet;
    }

    /**
     * 对数组按字段分组
     * @return array
     */
    public function arrayGroupBy($arr, $key)
    {
        $grouped = [];
        foreach ($arr as $value) {
            $grouped[$value[$key]][] = $value;
        }
        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array([this, 'arrayGroupBy'], $parms);
            }
        }
        return $grouped;
    }

    /**
     * 获取某个分类的所有子集
     * @return array
     */
    public function getAllChileren($category_list, $id = 0, $level = 1, $id_field = 'id', $pid_field = 'pid'){
        $subs = [
            'ids' => [],
            'list' => []
        ];
        foreach($category_list as $item){
            if($item[$pid_field] == $id){
                $item['level'] = $level;
                $subs['ids'][] = $item[$id_field];
                $subs['list'][] = $item;
                $tmp = $this->getAllChileren($category_list, $item[$id_field], $level + 1, $id_field, $pid_field);
                $subs['ids'] = array_merge($subs['ids'], $tmp['ids']);
                $subs['list'] = array_merge($subs['list'], $tmp['list']);
            }
        }
        return $subs;
    }
}
