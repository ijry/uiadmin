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

namespace uiadmin\apidoc\controller;

/**
 * 默认控制器
 *
 * @author jry <ijry@qq.com>
 */
class Index
{
    /**
     * OpenApiShema定义
     * 
     * @OA\Info(
     *   title="UiAdmin接口文档",
     *   version="1.3.0",
     *   @OA\Contact(
     *     email=""
     *   )
     * )
     * 
     * @OA\Server(url=API_HOST)
     * 
     * @OA\Schema(
     *   schema="apiWrap",
     *   required={"core", "msg"},
     *   @OA\Property(
     *      property="code",
     *      type="int",
     *      description="状态码200表示成功，其它表示有错误。"
     *   ),
     *   @OA\Property(
     *      property="msg",
     *      type="string",
     *      description="提示信息"
     *   ),
     *   @OA\Property(
     *      property="data",
     *      type="object",
     *      description="返回数据"
     *   )
     * )
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function index()
    {
    }
}
