<?php

namespace uiadmin\core\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

/**
 * 安装系统指令
 */
class Install extends Command
{
    /**
     * 配置指令
     */
    protected function configure()
    {
        $this->setName('uiadmin:install')->setDescription('install or update uiadmin');
    }

    /**
     * 执行指令
     * @param Input  $input
     * @param Output $output
     * @return null|int
     * @throws LogicException
     * @see setCode()
     */
    protected function execute(Input $input, Output $output)
    {
        if (!env('uiadmin.install')) {
            $output = \think\facade\Console::call('tauthz:publish', []);
            echo "执行think-authz发布完成\n";
            $output = \think\facade\Console::call('uiadmin:publish', []);
            echo "执行文件发布完成\n";
            $output = \think\facade\Console::call('migrate:run', []);
            echo "执行数据库迁移完成\n";
            $output = \think\facade\Console::call('seed:run', []);
            echo "执行数据库seeds迁移完成\n";
            file_put_contents(root_path() . '.env', "\n\n[UIADMIN]\nINSTALL = true\n", FILE_APPEND);
            echo "恭喜，UiAdmin安装完成！\n";
        } else {
            $output = \think\facade\Console::call('uiadmin:publish', []);
            echo "执行文件发布完成\n";
            $output = \think\facade\Console::call('migrate:run', []);
            echo "执行数据库迁移完成\n";
            $output = \think\facade\Console::call('seed:run', []);
            echo "执行数据库seeds迁移完成\n";
            echo "恭喜，更新完成！\n";
        }

        return $output->fetch();
    }
}

