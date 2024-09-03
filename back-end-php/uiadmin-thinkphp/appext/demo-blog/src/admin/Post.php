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


namespace demo\blog\admin;

use uiadmin\core\admin\BaseAdmin;
use uiadmin\core\attributes\MenuItem;
use demo\blog\model\Post as PostModel;

/**
 * 文章管理
 *
 * @author jry <ijry@qq.com>
 */
class Post extends BaseAdmin
{
    /**
     * 文章列表
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    #[MenuItem(["title" => "文章列表", "path" => "/blog/post/lists", "pmenu" => "/_tab_content",
        "menuType" => 1, "menuLayer" => "admin", "routeType" => "list",
        "apiSuffix" => "", "apiParams" => "", "apiMethod" => "GET", "sortnum" => 0])]
    public function lists()
    {
        $page = input('get.page/d') ?: 1;
        $limit = input('get.limit/d') ?: 10;
        $where = [];
        $keyword = input('get.keyword', '');

        // 文章列表
        $dataList = PostModel::where($where)
            ->page($page, $limit)
            ->order('id desc')
            ->select();
        $total = PostModel::where($where)
            ->count();

        // 构造动态页面数据
        $xyBuilderList = new \uiadmin\core\util\xybuilder\XyBuilderList();
        $xyBuilderList->init()
            ->setDataPage($total, $limit, $page)
            ->addTopButton('add', '添加文章', ['api' => '/v1/admin/blog/post/add']);
        $listData = $xyBuilderList
            ->addRightButton('edit', '修改', ['api' => '/v1/admin/blog/post/edit', 'title' => '修改文章信息'])
            ->addRightButton('delete', '删除', [
                'api' => '/v1/admin/blog/post/delete',
                'title' => '确认要删除该文章吗？',
                'modalType' => 'confirm',
                'width' => '600',
                'okText' => '确认删除',
                'cancelText' => '取消操作',
                'content' => '<p>删除后将清空绑定的所有登录验证记录</p>',
            ])
            ->addFilterItem('keyword', '关键字', 'text', $keyword, [
            ])
            ->addColumn('id' , 'ID', ['width' => '80px'])
            ->addColumn('title', '标题', ['width' => '120px'])
            ->addColumn('createTime', '创建时间', [
                'width' => '170px',
                'type' => 'template',
                'template' => 'time',
                'extend' => ['format' => 'yyyy-MM-dd HH:mm:ss']
            ])
            ->addColumn('status' , '状态', [
                'width' => '80px',
                'type' => 'template',
                'template' => 'switch',
                'options' => [ 1 => '正常', 0 => '禁用']
            ])
            ->addColumn('rightButtonList', '操作', [
                'minWidth' => '260px',
                'type' => 'template',
                'template' => 'rightButtonList'
            ])
            ->setDataList($dataList)
            ->getData();

        // 返回数据
        return json([
            'code' => 200, 'msg' => '成功', 'data' => [
                'listData' => $listData
            ]
        ]);
    }

    /**
     * 添加
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    #[MenuItem(["title" => "添加文章", "path" => "/blog/post/add", "pmenu" => "/blog/post/lists",
        "menuType" => 2, "menuLayer" => "admin", "routeType" => "form",
        "apiSuffix" => "", "apiParams" => "", "apiMethod" => "GET|POST", "sortnum" => 0])]
    public function add()
    {
        if (request()->isPost()) {
            // 数据验证
            $this->validateMake([
                'title'  => 'require',
            ],
            [
                'title.require' => '标题必须',
            ]);
            $data = input('post.');
            $this->validate($data);

            // 数据构造
            $dataDb = $data;
            if (count($dataDb) <= 0 ) {
                return json(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            $dataDb['status']   = 1;
            $dataDb['createTime'] = date('Y-m-d');

            // 存储数据
            $ret = PostModel::create($dataDb);
            if ($ret) {
                return json(['code' => 200, 'msg' => '添加文章成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '添加文章失败', 'data' => []]);
            }
        } else {
            // 构造动态页面数据
            $xyBuilderForm = new \uiadmin\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('post')
                ->addFormItem('title', '标题', 'text', '', [
                    'placeholder' => '标题',
                    'tip' => '标题'
                ])
                ->addFormItem('content', '内容', 'html', '', [
                    'placeholder' => '内容',
                    'tip' => '内容'
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写标题', 'trigger' => 'change'],
                ])
                ->setFormValues()
                ->getData();

            // 返回数据
            return json([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'formData' => $formData
                ]
            ]);
        }
    }

    /**
     * 修改
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    #[MenuItem(["title" => "修改文章", "path" => "/blog/post/edit", "pmenu" => "/blog/post/lists",
        "menuType" => 2, "menuLayer" => "admin", "routeType" => "form",
        "apiSuffix" => "/:id", "apiParams" => "", "apiMethod" => "GET|PUT", "sortnum" => 0])]
    public function edit($id)
    {
        // 文章信息
        $info = PostModel::where('id', $id)
            ->find();
        if (request()->isPut()) {
            // 数据验证
            $this->validateMake([
                    'title'  => 'require',
                ],
                [
                    'title.require' => '标题必须',
                ]);
            $data = input('post.');
            $this->validate($data);

            // 数据构造
            $dataDb = $data;
            if (count($dataDb) <= 0 ) {
                return json(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }

            /// 更新数据
            foreach ($dataDb as $key => $value) {
                if (isset($info[$key])) {
                    $info[$key] = $value;
                }
            }

            // 存储数据
            $ret = $info->save();
            if ($ret) {
                return json(['code' => 200, 'msg' => '修改文章信息成功', 'data' => []]);
            } else {
                return json(['code' => 0, 'msg' => '修改文章信息失败', 'data' => []]);
            }
        } else {
            // 构造动态页面数据
            $xyBuilderForm = new \uiadmin\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('put')
                ->addFormItem('title', '标题', 'text', '', [
                    'placeholder' => '标题',
                    'tip' => '标题'
                ])
                ->addFormItem('content', '内容', 'html', '', [
                    'placeholder' => '内容',
                    'tip' => '内容'
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写标题', 'trigger' => 'change'],
                ])
                ->setFormValues($info)
                ->getData();

            // 返回数据
            return json([
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
    #[MenuItem(["title" => "删除文章", "path" => "/blog/post/delete", "pmenu" => "/blog/post/lists",
        "menuType" => 3, "menuLayer" => "admin", "routeType" => "form",
        "apiSuffix" => "/:id", "apiParams" => "", "apiMethod" => "DELETE", "sortnum" => 0])]
    public function delete($id)
    {
        // 删除文章
        $ret = PostModel::where(['id' => $id])
            ->delete();
        if ($ret) {
            return json(['code' => 200, 'msg' => '删除成功', 'data' => []]);
        } else {
            return json(['code' => 0, 'msg' => '删除错误', 'data' => []]);
        }
    }
}
