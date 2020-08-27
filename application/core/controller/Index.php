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

namespace app\core\controller;

use think\Db;
use think\Request;
use app\core\controller\common\Home;

/**
 * 默认控制器
 *
 * @author jry <ijry@qq.com>
 */
class Index extends Home
{
    /**
     * API对接
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function api()
    {
        $apiBase = request()->domain() . request()->baseFile() . '/api/';
        $config_service = new \app\core\service\Config();
        $siteInfo = $config_service->getValueByModule('core', [['isDev', '=', 0]]);
        if (isset($siteInfo['admin2step'])) {
            $siteInfo['admin2step']['login'] = explode('|', $siteInfo['admin2step']['login']); 
        }

        // 返回
        return $this->return(['code' => 200, 'msg' => '成功', 'data' => [
            'lang' => 'php',
            'name'     => 'UniAdmin',
            'version' => '0.2.0',
            'api' => [
                'apiBase' => $apiBase,
                'apiLogin' => '/v1/admin/core/user/login',
                'apiAdmin' => '/v1/admin/core/index/index',
                'apiMenuTrees' => '/v1/admin/core/menu/trees',
                'apiMenuLists' => '/v1/admin/core/menu/lists',
                'apiConfig' => '/v1/core/index/config?module=core',
                'apiUserInfo' => '/v1/core/user/info'
            ],
            'siteInfo' => $siteInfo
        ]], true);
    }

    /**
     * 获取基本配置
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function config($module = 'core')
    {
        // 获取配置列表
        $dataList = Db::name('core_config')
            ->field('configCate,configType,name,value')
            ->where('isDev', '=', 0) // 只允许非开发者配置，因为开发者配置很可能包含appkey等敏感数据
            ->where('module', '=', $module)
            ->select();
        $return = [];
        foreach ($dataList as $key => &$value) {
            if ($value['configType'] == 'array') {
                $config_list[$value['name']] = parse_attr($value['value']);
            } else if (in_array($value['configType'], ['images', 'files'])) {
                if ($value['value'] == '') {
                    $value['value'] = [];
                } else {
                    $value['value'] = json_decode($value['value'], true);
                }
                $config_list[$value['name']] = $value['value'];
            } else {
                $config_list[$value['name']] = $value['value'];
            }
        }

        // 返回数据
        return $this->return([
            'code' => 200,
            'msg'  => '成功',
            'data' => [
                'config_list' => $config_list
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
        $login = parent::isLogin();
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move( './static/uploads');
        if ($info) {
            // 成功上传后 获取上传信息
            return $this->return([
                'code' => 200,
                'msg' => '上传成功',
                'data' => [
                    'name' => $info->getSaveName(),
                    'url' => rtrim(rtrim(request()->root(true), 'index.php'), '/') . '/static/uploads/' . $info->getSaveName()
                ]
            ]);
        } else {
            // 返回数据
            return $this->return([
                'code' => 0,
                'msg'  => $file->getError(),
                'data' => [
                    'name' => '',
                    'url' => ''
                ]
            ]);
        }
    }
}
