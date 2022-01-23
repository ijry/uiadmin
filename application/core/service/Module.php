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

namespace app\core\service;
use think\Model;
use think\Db;

class Module extends Model
{
    protected $core_module,$core_config,$core_menu;

    public function __construct()
    {
        $this->core_menu = new \app\core\model\Menu();
        $this->core_module = new \app\core\model\Module();
        $this->core_config = new \app\core\model\Config();
    }

    /**
     * 导出模块
     * @return array
     * @author jry <ijry@qq.com>
     */
    public function export($id, $mysql_conn)
    {
        $info = $this->core_module
            ->field('name,title,description,developer,website,version,build,hasTaglib,hasCommand')
            ->where(['id' => $id])
            ->find()->toArray();;
        if (!$info) {
            throw new \Exception("不存在模块信息", 0);
        }
        if (!is_dir(env('app_path') . $info['name'])) {
            return true;
        }

        // 导出基本信息
        $expotInfo = [];
        $expotInfo['info'] = $info;

        // 导出配置
        $expotInfo['config'] = $this->core_config
            ->field('module,configCate,name,title,configType,defaultValue,placeholder,tip,options,isSystem,isDev,sortnum,status')
            ->where('module', '=', $info['name'])
            ->select()->toArray();

        // 导出API
        $expotInfo['api'] = $this->core_menu
            ->field('module,icon,path,pmenu,title,tip,menuLayer,menuType,routeType,apiPrefix,apiSuffix,apiParams,apiMethod,apiExt,isHide,isDev')
            ->where('module', '=', $info['name'])
            ->select()->toArray();
        $database = config('database.database');
        mysqli_select_db($mysql_conn, $database);
        mysqli_query($mysql_conn, 'SET NAMES utf8');

        // 获取表
        $table_result = mysqli_query($mysql_conn, 'show tables');
        $tables = array();
        while ($row = mysqli_fetch_array($table_result)) {
            if ($row[0] == config('database.prefix') . $info['name'] || \think\helper\Str::startsWith($row[0], config('database.prefix') . $info['name'] . '_')) {
                $tables[]['tableName'] = $row[0];
            }
        }
        // 循环取得所有表的备注及表中列消息
        if (is_file(env('app_path') . $info['name'] . '/install/install.php')) {
            $install_config = include env('app_path') . $info['name'] . '/install/install.php';
        }
        foreach ($tables as $k => $v) {
            $sql = 'show create table ' . $v['tableName'];
            $table_result = mysqli_query($mysql_conn, $sql);
            while ($t = mysqli_fetch_array($table_result)) {
                $t[1] = preg_replace('/AUTO\_INCREMENT=\d*\ /i', "", $t[1]);
                $tmp = explode("\n", $t[1]);
                $tables[$k]['tableCreate'] = $tmp;
            }

            // 获取表数据
            $limit = 1000;
            $tableName_array = explode('_', $v['tableName']);
            unset($tableName_array[0]);
            $tableName_no_prefix = implode('_', $tableName_array);
            if (isset($install_config['export']['table']['rowLimit'][$tableName_no_prefix])) {
                $limit = $install_config['export']['table']['rowLimit'][$tableName_no_prefix];
            }
            if ($limit > 0) {
                $tables[$k]['tableRows'] = Db::table($v['tableName'])->limit($limit)->select();
            } else {
                $tables[$k]['tableRows'] = [];
            }
        }
        $expotInfo['tables'] = $tables;

        // 写入文件
        // dump($expotInfo);
        file_put_contents(env('app_path') . $info['name'] .'/install/install.json', json_encode($expotInfo, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
    }

    /**
     * 存在模块
     * @return array
     * @author jry <ijry@qq.com>
     */
    public function isExist($name)
    {
        $isExist = $this->core_module
            ->where('name', '=', $name)
            ->where('status', '=', 1)
            ->count();
        if ($isExist) {
            return true;
        }
        return false;
    }

    /**
     * 获得下级模块列表
     * @return array
     * @author jry <ijry@qq.com>
     */
    public function getChildList($name)
    {
        $dataList = $this->core_module
            ->where('pname', '=', $name)
            ->select()
            ->toArray();
        foreach ($dataList as $key => &$value) {
            $value['value'] = $value['name'];
        }
        return $dataList;
    }
}
