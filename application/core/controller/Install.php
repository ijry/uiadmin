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
 * 安装控制器
 *
 * @author jry <ijry@qq.com>
 */
class Install extends Home
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * 第一步
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function step1()
    {
        return $this->return(['code' => 200, 'msg' => '成功']);
    }

    /**
     * 第二步
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function step2()
    {
        if (request()->isPost()) {
            if (session('error')) {
                return $this->return(['code' => 0, 'msg' => '环境检测没有通过，请调整环境后重试！']);
            } else {
                return $this->return(['code' => 200, 'msg' => '恭喜您环境检测通过!']);
            }
        } else {
            session('step', '2');
            session('error', false);
            return $this->return(['code' => 200, 'msg' => '成功', 'data' => [
                'check_env' => $this->check_env(),
                'check_dirfile' => $this->check_dirfile(),
                'check_func_and_ext' => $this->check_func_and_ext(),
            ]]);
        }
    }

    /**
     * 第三步
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function step3()
    {
        if (request()->isPost()) {
            $db = input('post.');
            if (empty($db['type']) || empty($db['hostname'])
                || empty($db['hostport']) || empty($db['database'])
                || empty($db['username']) || empty($db['password'])) {
                return $this->return(['code' => 0, 'msg' => '请填写完整的数据库配置']);
            } else {
                // 缓存数据库配置
                session('db_config', $db);
                session('error', null);
                session('install', null);
                session('error_msg', null);

                // 创建数据库连接
                $db_name = $db['database'];
                unset($db['database']); // 防止不存在的数据库导致连接数据库失败
                $db_instance = Db::connect($db);

                // 检测数据库连接
                $result1 = $db_instance->execute('select version()');
                if (!$result1) {
                    return $this->return(['code' => 0, 'msg' => '数据库连接失败，请检查数据库配置！']);
                }

                // 用户选择不覆盖情况下检测是否已存在数据库
                if (input('post.cover') == '1') {
                    // 检测是否已存在数据库
                    $result2 = $db_instance->query('SELECT * FROM information_schema.schemata WHERE schema_name="' . $db_name . '"');
                    if ($result2) {
                        return $this->return(['code' => 0, 'msg' => '该数据库已存在，请更换名称！如需覆盖，请选中覆盖按钮！']);
                    }

                    // 创建数据库
                    $sql = "CREATE DATABASE IF NOT EXISTS `{$db_name}` DEFAULT CHARACTER SET utf8";
                    if (!$db_instance->execute($sql)) {
                        return $this->return(['code' => 0, 'msg' => $db_instance->getError()]);
                    }
                }
                return $this->return(['code' => 200, 'msg' => '成功']);
            }
        } else {
            session('step', '3');
            session('error', false);
            return $this->return(['code' => 200, 'msg' => '成功', 'data' => [
                'rand' => strtolower(\think\helper\Str::random(6))
            ]]);
        }
    }

    /**
     * 第四步
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function step4()
    {
        session('step', '4');
        session('error', null);
        session('install', null);
        session('error_msg', null);
        echo $this->return(['code' => 200, 'msg' => '正在安装']);

        try {
            // 连接数据库
            $db_config   = session('db_config');
            $db_config['prefix'] = config('database.prefix');
            $db_instance = Db::connect($db_config);

            // 安装核心模块
            $module_insall = file_get_contents(env('app_path') . 'core/install/install.json');
            $module_insall = json_decode($module_insall, true);

            // 导入数据表
            foreach ($module_insall['tables'] as $key => $value) {
                if (isset($value['tableCreate'])) {
                    $value['tableCreate'] = implode("", $value['tableCreate']);
                    if (false === $db_instance->execute($value['tableCreate'])) {
                        throw new \Exception($value['tableName'] . "创建出错", 0);
                    }
                }
                if (isset($value['tableRows']) && count($value['tableRows']) > 0) {
                    if (false === $db_instance->table($value['tableName'])->insertAll($value['tableRows'])) {
                        throw new \Exception($value['tableName'] . "添加记录出错", 0);
                    }
                }
            }

            // 导入配置
            foreach($module_insall['config'] as $key => &$val) {
                $val['value'] = $val['default'];
            }
            $db_instance->table('xy_core_config')->insertAll($module_insall['config']);
            // 导入菜单及API
            $db_instance->table('xy_core_menu')->insertAll($module_insall['api']);
            // 创建管理员账号
            $key = \think\helper\Str::random(64);
            $password = user_md5('uniadmin', $key);
            $user = $db_instance->table('xy_core_user')->insert([
                'id' => 1,
                'key' => $key,
                'nickname' => '超级管理员',
                'username' => 'admin',
                'password' => $password,
                'avatar' => '',
                'status' => 1,
                'roles' => implode(',', ['super_admin']),
                'registerTime' => time()
            ]);
        } catch (\Exception $e) {
            echo($e);
            exit;
        }

        // 创建配置文件
        $conf = $this->write_config($db_config);

        session('install', true);
    }

    /**
     * 安装完成
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function step5()
    {
        if (request()->isPost()) {
            if (session('install') == true) {
                return $this->return(['code' => 200, 'msg' => '安装成功']);
            }
            return $this->return(['code' => 0, 'msg' => '正在安装', 'data' => [
                'error' => session('error'),
                'error_msg' => session('error_msg')
            ]]);
        } else {
            session('step', null);
            session('error', null);
            session('install', null);
            session('error_msg', null);
            return $this->return(['code' => 200, 'msg' => '成功']);
        }
    }

    /**
     * 创建数据表
     * @param  resource $db 数据库连接资源
     */
    private function create_tables($db, $prefix = 'ia')
    {
        // 读取SQL文件
        if (is_file(env('root_path') . 'data/install.sql')) {
            $sql = file_get_contents(env('root_path') . 'data/install.sql');
        } else {
            $sql = file_get_contents(env('app_path') . 'core/install/install.sql');
        }
        $sql = str_replace("\r", "\n", $sql);
        $sql = explode(";\n", $sql);

        // 替换表前缀
        $orginal = 'ia_';
        $sql     = str_replace(" `{$orginal}", " `{$prefix}", $sql);

        // 开始安装
        foreach ($sql as $value) {
            $value = trim($value);
            if (empty($value)) {
                continue;
            }

            if (substr($value, 0, 12) == 'CREATE TABLE') {
                $name = preg_replace("/^CREATE TABLE `(\w+)` .*/s", "\\1", $value);
                $msg  = "创建数据表{$name}";
                if (false === $db->execute($value)) {
                    session('error', true);
                    session('error_msg', $msg . '...失败！');
                    return false;
                }
            } else {
                $db->execute($value);
            }
        }
    }

    /**
     * 写入配置文件
     * @param  array $config 配置信息
     */
    private function write_config($config, $auth = '')
    {
        if (is_array($config)) {
            // 读取配置内容
            $conf = file_get_contents(env('root_path') . 'config/.env');

            // 替换配置项
            foreach ($config as $name => $value) {
                $conf = str_replace("[{$name}]", $value, $conf);
            }

            // 写入应用配置文件
            if (!file_put_contents(env('root_path') . '.env', $conf)) {
                session('error', true);
                session('error_msg', '配置文件写入失败！');
                return false;
            }
            return true;
        }
    }

    /**
     * 系统环境检测
     * @return array 系统环境数据
     * @author jry <598821125@qq.com>
     */
    private function check_env()
    {
        $items = array(
            '0'     => array(
                'title'   => '操作系统',
                'limit'   => '不限制',
                'current' => PHP_OS,
                'icon'    => 'el-icon-check text-success',
            ),
            '1'    => array(
                'title'   => 'PHP版本',
                'limit'   => '5.6+',
                'current' => PHP_VERSION,
                'icon'    => 'el-icon-check text-success',
            ),
            '2' => array(
                'title'   => '附件上传',
                'limit'   => '不限制',
                'current' => ini_get('file_uploads') ? ini_get('upload_max_filesize') : '未知',
                'icon'    => 'el-icon-check text-success',
            ),
            '3'     => array(
                'title'   => 'GD库',
                'limit'   => '2.0+',
                'current' => '未知',
                'icon'    => 'el-icon-check text-success',
            ),
            '4'   => array(
                'title'   => '磁盘空间',
                'limit'   => '200M+',
                'current' => '未知',
                'icon'    => 'el-icon-check text-success',
            ),
        );

        // PHP环境检测
        if ($items['1']['current'] < 5.4) {
            $items['1']['icon'] = 'el-icon-close text-danger';
            session('error', true);
        }

        // GD库检测
        $tmp = function_exists('gd_info') ? gd_info() : array();
        if (!$tmp['GD Version']) {
            $items['3']['current'] = '未安装';
            $items['3']['icon']    = 'el-icon-close text-danger';
            session('error', true);
        } else {
            $items['3']['current'] = $tmp['GD Version'];
        }
        unset($tmp);

        // 磁盘空间检测
        if (function_exists('disk_free_space')) {
            $disk_size                = floor(disk_free_space('./') / (1024 * 1024)) . 'M';
            $items['4']['current'] = $disk_size . 'MB';
            if ($disk_size < 200) {
                $items['4']['icon'] = 'el-icon-close text-danger';
                session('error', true);
            }
        }
        return $items;
    }

    /**
     * 目录，文件读写检测
     * @return array 检测数据
     * @author jry <598821125@qq.com>
     */
    private function check_dirfile()
    {
        $items = array(
            '0' => array(
                'type'  => 'dir',
                'path'  => env('root_path') . 'runtime',
                'current' => '可写',
                'icon'  => 'el-icon-check text-success',
            ),
            '1' => array(
                'type'  => 'dir',
                'path'  => env('root_path') . 'public',
                'current' => '可写',
                'icon'  => 'el-icon-check text-success',
            ),
            '2' => array(
                'type'  => 'dir',
                'path'  => env('root_path') . 'public/static/uploads',
                'current' => '可写',
                'icon'  => 'el-icon-check text-success',
            )
        );

        foreach ($items as &$val) {
            $path = $val['path'];
            if ('dir' === $val['type']) {
                if (!is_writable($path)) {
                    if (is_dir($path)) {
                        $val['current'] = '不可写';
                        $val['icon']  = 'el-icon-close text-danger';
                        session('error', true);
                    } else {
                        $val['current'] = '不存在';
                        $val['icon']  = 'el-icon-close text-danger';
                        session('error', true);
                    }
                }
            } else {
                if (file_exists($path)) {
                    if (!is_writable($path)) {
                        $val['current'] = '不可写';
                        $val['icon']  = 'el-icon-close text-danger';
                        session('error', true);
                    }
                } else {
                    if (!is_writable(dirname($path))) {
                        $val['current'] = '不存在';
                        $val['icon']  = 'el-icon-close text-danger';
                        session('error', true);
                    }
                }
            }
        }
        return $items;
    }

    /**
     * 函数检测
     * @return array 检测数据
     */
    private function check_func_and_ext()
    {
        $items = array(
            '0' => array(
                'type'    => 'ext',
                'name'    => 'pdo',
                'title'   => '支持',
                'current' => extension_loaded('pdo'),
                'icon'    => 'el-icon-check text-success',
            ),
            '1' => array(
                'type'    => 'ext',
                'name'    => 'pdo_mysql',
                'title'   => '支持',
                'current' => extension_loaded('pdo_mysql'),
                'icon'    => 'el-icon-check text-success',
            ),
            '2' => array(
                'type'  => 'func',
                'name'  => 'file_get_contents',
                'title' => '支持',
                'icon'  => 'el-icon-check text-success',
            ),
            '3' => array(
                'type'  => 'func',
                'name'  => 'mb_strlen',
                'title' => '支持',
                'icon'  => 'el-icon-check text-success',
            ),
        );
        foreach ($items as &$val) {
            switch ($val['type']) {
                case 'ext':
                    if (!$val['current']) {
                        $val['title'] = '不支持';
                        $val['icon']  = 'el-icon-close text-danger';
                        session('error', true);
                    }
                    break;
                case 'func':
                    if (!function_exists($val['name'])) {
                        $val['title'] = '不支持';
                        $val['icon']  = 'el-icon-close text-danger';
                        session('error', true);
                    }
                    break;
            }
        }
        return $items;
    }
}
