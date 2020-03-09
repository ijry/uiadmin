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
namespace app\cms\controller\admin;

use think\Db;
use think\Validate;
use think\facade\Request;
use app\core\controller\common\Admin;
use app\core\util\Tree;

/**
 * 文章管理
 *
 * @author jry <ijry@qq.com>
 */
class Post extends Admin
{
    private $cms_cate,$cms_post;

    protected function initialize()
    {
        parent::initialize();
        $this->cms_cate = new \app\cms\model\Cate();
        $this->cms_post = new \app\cms\model\Post();
    }

    /**
     * 列表
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function lists($cid)
    {
        // 列表
        $dataList = $this->cms_post
            ->where('cid', $cid)
            ->select()
            ->toArray();
        $tree      = new Tree();
        $dataTree = $tree->list2tree($dataList);

        // 构造动态页面数据
        $xyBuilderList = new \app\core\util\xybuilder\XyBuilderList();
        $listData = $xyBuilderList->init()
            ->addTopButton('add', '添加', ['api' => '/v1/admin/cms/post/add/' . $cid])
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/cms/post/edit', 'title' => '修改'])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/cms/cate/delete',
                'title' => '确认要删除该文章吗？',
                'modalType' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p>删除后前台用户将无法查看</p>',
            ])
            ->addColumn('id' , 'ID', ['width' => '50px'])
            ->addColumn('title', '标题', ['minWidth' => '100px'])
            ->addColumn('viewCount', '阅读数', ['width' => '100px'])
            ->addColumn('sortnum', '排序', ['width' => '50px'])
            ->addColumn('rightButtonList', '操作', [
                'minWidth' => '50px',
                'type' => 'template',
                'template' => 'rightButtonList'
            ])
            ->setDataList($dataTree)
            ->getData();

        //返回数据
        return $this->return(
            [
                'code' => 200, 'msg' => '成功', 'data' => [
                    'listData' => $listData
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
    public function add($cid)
    {
        if(request()->isPost()){
            // 数据验证
            $validate = Validate::make([
                'cid'  => 'number',
                'title' => 'require'
            ],
            [
                'cid.number' => 'cid必须数字',
                'title.require' => '文章标题必须'
            ]);
            $data = input('post.');
            if (!$validate->check($data)) {
                return $this->return(['code' => 200, 'msg' => $validate->getError(), 'data' => []]);
            }

            // 数据构造
            $data_db = $data;
            if (count($data_db) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            $data_db['status'] = 1;
            $data_db['sortnum'] = isset($data_db['sortnum']) ? $data_db['sortnum'] : 0;

            // 存储数据
            $ret = $this->cms_post->save($data_db);
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '添加成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '添加失败:' . $this->cms_post->getError(), 'data' => []]);
            }
        } else {
            // 获树状列表
            $cateList = $this->cms_cate
                ->order('sortnum asc')
                ->select()->toArray();
            $tree      = new Tree();
            $cate_tree = $tree->array2tree($cateList, 'title', 'id', 'pid', 0, false);
            $cate_tree_select = [];
            foreach ($cate_tree as $key1 => $val1) {
                $cate_tree_select[$key1]['title'] = $val1['title_show'];
                $cate_tree_select[$key1]['value'] = $val1['id'];
            }

            // 构造动态页面数据
            $xyBuilderForm = new \app\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('post')
                ->addFormItem('cid', '上级', 'select', $cid, [
                    'options' => $cate_tree_select
                ])
                ->addFormItem('title', '文章标题', 'text', '', [
                    'placeholder' => '请输入文章标题'
                ])
                ->addFormItem('content', '文章内容', 'tinymce', '', [
                ])
                ->addFormItem('sortnum', '排序', 'text', '')
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写文章标题', 'trigger' => 'change'],
                ])
                ->setFormValues()
                ->getData();

            //返回数据
            return $this->return(
                [
                    'code' => 200,
                    'msg' => '成功',
                    'data' => [
                        'formData' => $formData
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
                'cid'  => 'require|number',
                'title' => 'require'
            ],
            [
                'cid.number' => 'cid必须数字',
                'cid.require' => '文章标题必须'
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
            $ret = $this->cms_post->update($data_db, ['id' => $id]);
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '修改成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '修改失败:' . $this->cms_post->getError(), 'data' => []]);
            }
        } else {
            //获取信息
            $info = $this->cms_post
                ->where('id', $id)
                ->find();

            // 获树状列表
            $cateList = $this->cms_cate
                ->order('sortnum asc')
                ->select()->toArray();
            $tree      = new Tree();
            $cate_tree = $tree->array2tree($cateList, 'title', 'id', 'pid', 0, false);
            $cate_tree_select = [];
            foreach ($cate_tree as $key1 => $val1) {
                $cate_tree_select[$key1]['title'] = $val1['title_show'];
                $cate_tree_select[$key1]['value'] = $val1['id'];
            }

            //构造动态页面数据
            $xyBuilderForm = new \app\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('put')
               ->addFormItem('cid', '分类', 'select', 0, [
                    'tip' => '分类',
                    'options' => $cate_tree_select
                ])
                ->addFormItem('title', '文章标题', 'text', '', [
                    'placeholder' => '请输入分类名称'
                ])
                ->addFormItem('content', '文章内容', 'tinymce', '', [
                ])
                ->addFormItem('sortnum', '排序', 'text', '')
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写文章标题', 'trigger' => 'change'],
                ])
                ->setFormValues($info)
                ->getData();

            //返回数据
            return $this->return([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'formData' => $formData
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
        $ret = $this->cms_post
            ->where('id', $id)
            ->find()
            ->delete();
        if ($ret) {
            return $this->return(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return $this->return(['code' => 0, 'msg' => '删除错误:' . $this->cms_post->getError(), 'data' => []]);
        }
    }
}
