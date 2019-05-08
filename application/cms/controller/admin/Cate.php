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
namespace app\cms\controller\admin;

use think\Db;
use think\Validate;
use think\facade\Request;
use app\core\controller\common\Admin;
use app\core\util\Tree;

/**
 * 分类管理
 *
 * @author jry <ijry@qq.com>
 */
class Cate extends Admin
{
    private $cms_cate;

    protected function initialize()
    {
        parent::initialize();

        $this->cms_cate = new \app\cms\model\Cate();
    }

    /**
     * 列表
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function trees()
    {
        // 列表
        $data_list = $this->cms_cate->select()->toArray();
        $tree      = new Tree();
        $data_tree = $tree->list2tree($data_list);

        // 构造动态页面数据
        $ibuilder_list = new \app\core\util\ibuilder\IbuilderList();
        $list_data = $ibuilder_list->init()
            ->addTopButton('add', '添加', ['api' => '/v1/admin/cms/cate/add'])
            ->addRightButton('member', '文章管理', [
                'modal_type' => 'list',
                'api' => '/v1/admin/cms/post/lists',
                'api_suffix' => ['id'],
                'width' => '900',
                'title' => '文章列表'
            ])
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/cms/cate/edit', 'title' => '修改分类'])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/cms/cate/delete',
                'title' => '确认要删除该分类吗？',
                'modal_type' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p><p>如果该角色下有文章需要先删除或者移动</p><p>如果该分类下有子分类需要先移除才可以删除</p></p>',
            ])
            ->addColumn('id' , 'ID', ['width' => '50px'])
            ->addColumn('title', '分类名称', ['minWidth' => '100px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('right_button_list', '操作', [
                'minWidth' => '50px',
                'type' => 'template',
                'template' => 'right_button_list'
            ])
            ->setDataList($data_tree)
            ->getData();

        // 返回数据
        return $this->return(
            [
                'code' => 200, 'msg' => '成功', 'data' => [
                    'list_data' => $list_data
                ]
            ]
        );
    }

    /**
     * 添加
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function add()
    {
        if(request()->isPost()){
            // 数据验证
            $validate = Validate::make([
                'pid'  => 'number',
                'title' => 'require'
            ],
            [
                'pid.number' => 'pid必须数字',
                'title.require' => '分类名称必须'
            ]);
            $data = input('post.');
            if (!$validate->check($data)) {
                return $this->return(['code' => 0, 'msg' => $validate->getError(), 'data' => []]);
            }

            // 数据构造
            $data_db = $data;
            if (count($data_db) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            $data_db['status'] = 1;
            $data_db['sortnum'] = isset($data_db['sortnum']) ? $data_db['sortnum'] : 0;

            // 存储数据
            $ret = $this->cms_cate->save($data_db);
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '添加成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '添加失败:' . $this->cms_cate->getError(), 'data' => []]);
            }
        } else {
            // 获树状列表
            $cate_list = $this->cms_cate
                ->order('sortnum asc')
                ->select()->toArray();
            $tree      = new Tree();
            $cate_tree = $tree->array2tree($cate_list, 'title', 'id', 'pid', 0, false);
            $cate_tree_select = [];
            foreach ($cate_tree as $key1 => $val1) {
                $cate_tree_select[$key1]['title'] = $val1['title_show'];
                $cate_tree_select[$key1]['value'] = $val1['id'];
            }

            // 构造动态页面数据
            $ibuilder_form = new \app\core\util\ibuilder\IbuilderForm();
            $form_data = $ibuilder_form->init()
                ->setFormMethod('post')
                ->addFormItem('pid', '上级', 'select', 0, [
                    'options' => $cate_tree_select
                ])
                ->addFormItem('title', '分类名称', 'text', '', [
                    'placeholder' => '请输入分类名称'
                ])
                ->addFormItem('sortnum', '排序', 'text', '')
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写分类名称', 'trigger' => 'change'],
                ])
                ->setFormValues()
                ->getData();

            // 返回数据
            return $this->return(
                [
                    'code' => 200,
                    'msg' => '成功',
                    'data' => [
                        'form_data' => $form_data
                    ]
                ]
            );
        }
    }

    /**
     * 修改
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function edit($id)
    {
        if(request()->isPut()){
            // 数据验证
            $validate = Validate::make([
                'pid'  => 'number',
                'title' => 'require'
            ],
            [
                'pid.number' => 'pid必须数字',
                'title.require' => '分类名称必须'
            ]);
            $data = input('post.');
            if (!$validate->check($data)) {
                return $this->return(['code' => 0, 'msg' => $validate->getError(), 'data' => []]);
            }

            // 数据构造
            $data_db = $data;
            if (count($data_db) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }

            // 存储数据
            $ret = $this->cms_cate->update($data_db, ['id' => $id]);
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '修改成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '修改失败:' . $this->cms_cate->getError(), 'data' => []]);
            }
        } else {
            // 获取信息
            $info = $this->cms_cate
                ->where('id', $id)
                ->find();

            // 获树状列表
            $cate_list = $this->cms_cate
                ->order('sortnum asc')
                ->select()->toArray();
            $tree      = new Tree();
            $cate_tree = $tree->array2tree($cate_list, 'title', 'id', 'pid', 0, false);
            $cate_tree_select = [];
            foreach ($cate_tree as $key1 => $val1) {
                $cate_tree_select[$key1]['title'] = $val1['title_show'];
            }

            //构造动态页面数据
            $ibuilder_form = new \app\core\util\ibuilder\IbuilderForm();
            $form_data = $ibuilder_form->init()
                ->setFormMethod('put')
               ->addFormItem('pid', '上级', 'select', 0, [
                    'tip' => '上级分类',
                    'options' => $cate_tree_select
                ])
                ->addFormItem('title', '分类名称', 'text', '', [
                    'placeholder' => '请输入分类名称'
                ])
                ->addFormItem('sortnum', '排序', 'text', '')
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写分类名称', 'trigger' => 'change'],
                ])
                ->setFormValues($info)
                ->getData();

            //返回数据
            return $this->return([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'form_data' => $form_data
                ]
            ]);
        }
    }

    /**
     * 删除
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function delete($id)
    {
        $ret = $this->cms_cate
            ->where('id', $id)
            ->find()
            ->delete();
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return $this->return(['code' => 0, 'msg' => '删除错误:' . $this->cms_cate->getError(), 'data' => []]);
        }
    }
}
