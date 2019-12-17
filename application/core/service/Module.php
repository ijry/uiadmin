<?php
/**
 * +----------------------------------------------------------------------
 * | xycloud [ 渐进式后端云 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2020 http://jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
 * | 版权申明：此源码不是一个自由软件，是jry推出的私有源码，严禁在未经许可的情况下
 * | 拷贝、复制、传播、使用此源码的任意代码，如有违反，请立即删除，否则您将面临承担相应
 * | 法律责任的风险。如果需要取得官方授权，请联系官方QQ598821125。
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
     * @author jry <598821125@qq.com>
     */
    public function export($id, $mysql_conn)
    {
        $info = $this->core_module
            ->field('name,title,description,developer,website,version,build,has_taglib,has_command')
            ->where(['id' => $id])
            ->find()->toArray();;
        if (!$info) {
            throw new \Exception("不存在模块信息", 0);
        }
        if (!is_dir(env('app_path') . $info['name'])) {
            return true;
        }

        // 导出基本信息
        $expot_info = [];
        $expot_info['info'] = $info;

        // 导出配置
        $expot_info['config'] = $this->core_config
            ->field('module,config_cate,name,title,config_type,default,placeholder,tip,options,is_system,is_dev,sortnum,status')
            ->where('module', '=', $info['name'])
            ->select()->toArray();

        // 导出API
        $expot_info['api'] = $this->core_menu
            ->field('module,icon,path,pmenu,title,tip,menu_layer,menu_type,route_type,api_prefix,api_suffix,api_params,api_method,api_ext,doc,is_hide')
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
                $tables[]['table_name'] = $row[0];
            }
        }
        // 循环取得所有表的备注及表中列消息
        if (is_file(env('app_path') . $info['name'] . '/install/install.php')) {
            $install_config = include env('app_path') . $info['name'] . '/install/install.php';
        }
        foreach ($tables as $k => $v) {
            $sql = 'show create table ' . $v['table_name'];
            $table_result = mysqli_query($mysql_conn, $sql);
            while ($t = mysqli_fetch_array($table_result)) {
                $t[1] = preg_replace('/AUTO\_INCREMENT=\d*\ /i', "", $t[1]);
                $tmp = explode("\n", $t[1]);
                $tables[$k]['table_create'] = $tmp;
            }

            // 获取表数据
            $limit = 1000;
            $table_name_array = explode('_', $v['table_name']);
            unset($table_name_array[0]);
            $table_name_no_prefix = implode('_', $table_name_array);
            if (isset($install_config['export']['table']['row_limit'][$table_name_no_prefix])) {
                $limit = $install_config['export']['table']['row_limit'][$table_name_no_prefix];
            }
            if ($limit > 0) {
                $tables[$k]['table_rows'] = Db::table($v['table_name'])->limit($limit)->select();
            } else {
                $tables[$k]['table_rows'] = [];
            }
        }
        $expot_info['tables'] = $tables;

        // 写入文件
        // dump($expot_info);
        file_put_contents(env('app_path') . $info['name'] .'/install/install.json', json_encode($expot_info, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
    }

    /**
     * 存在模块
     * @return array
     * @author jry <598821125@qq.com>
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
     * @author jry <598821125@qq.com>
     */
    public function getChildList($name)
    {
        $data_list = $this->core_module
            ->where('pname', '=', $name)
            ->select()
            ->toArray();
        foreach ($data_list as $key => &$value) {
            $value['value'] = $value['name'];
        }
        return $data_list;
    }
}
