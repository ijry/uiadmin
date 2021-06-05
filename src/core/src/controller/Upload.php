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

namespace uniadmin\core\controller;

use think\Request;

/**
 * 核心控制器
 *
 * @author jry <ijry@qq.com>
 */
class Upload
{
    /**
     * 分片上传合并上传
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function merge()
    {
        // $login = parent::isLogin();
        $context = input('post.context');
        $chunks = (int) input('post.chunks');
        // 合并后的文件名
        $childpath = 'uploads';
        if ($this->cloudId > 0) {
            $childpath = 'tenant_' . $this->cloudId;
        }
        $filename = app()->getRootPath() . 'public/storage/' . $childpath . '/' . substr($context, 7); 
        for($i = 1; $i <= $chunks; ++$i){
            $file = app()->getRootPath() . 'public/storage/' . $childpath . '/' . $context. '/' . $i . '.tmp'; // 读取单个切块
            $content = file_get_contents($file);
            if(!file_exists($filename)){
                $fd = fopen($filename, "w+");
            }else{
                $fd = fopen($filename, "a");
            }
            fwrite($fd, $content); // 将切块合并到一个文件上
        }
        return json([
            'code' => 200,
            'msg' => '上传成功',
            'data' => [
                'name' => $filename,
                'path' => '/storage/' . $childpath . '/' . $filename,
                'url' => request()->scheme() . '://' . $_SERVER['HTTP_HOST'] . '/storage/' . $childpath . '/' . $filename
            ]
        ]);
    }

    /**
     * 文件上传
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function upload()
    {
        try {
            // $login = parent::isLogin();

            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('file');
            // 上传到本地服务器
            $childpath = 'uploads';
            if ($this->cloudId > 0) {
                $childpath = 'tenant_' . $this->cloudId;
            }
            if (input('post.context')) {
                $savename = \think\facade\Filesystem::disk('public')->putFileAs(
                    $childpath . '/' . input('post.context'),
                    $file,
                    input('post.index') . '.' . $file->getExtension());
            } else {
                $savename = \think\facade\Filesystem::disk('public')->putFile($childpath, $file);
            }

            if ($savename) {
                // 成功上传后 获取上传信息
                return json([
                    'code' => 200,
                    'msg' => '上传成功',
                    'data' => [
                        'name' => $file->getOriginalName(),
                        'path' => $savename,
                        'url' => request()->root(true) . '/storage/' . $savename
                    ]
                ]);
            } else {
                // 返回数据
                return json([
                    'code' => 0,
                    'msg'  => '上传出错',
                    'data' => [
                        'name' => '',
                        'path' => '',
                        'url' => ''
                    ]
                ]);
            }
        } catch (\Exception $e) {
            // 返回数据
            return json([
                'code' => 0,
                'msg'  => $e->getMessage(),
                'data' => [
                    'name' => '',
                    'path' => '',
                    'url' => ''
                ]
            ]);
        }
    }
}
