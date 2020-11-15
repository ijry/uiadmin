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

namespace app\core\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use app\core\model\User as coreUserModel;

class Index extends Command
{
    protected function configure()
    {
        $this->setName('coreCommand')
            ->addArgument('type', Argument::OPTIONAL, "任务名称")
            // ->addOption('id', null, Option::VALUE_REQUIRED, '任务ID')
            ->setDescription('核心模块命令行');
    }

    protected function execute(Input $input, Output $output)
    {
        try {
            $type = trim($input->getArgument('type'));
            switch ($type) {
                case 'restpwd':
                    // 重置密码
                    $info = coreUserModel::where('cloudAlias', 0)
                        ->where('id', 1);
                    $info['password'] = user_md5('uniadmin', $info['key']);
                    $ret = $info->save();
                    if ($ret) {
                        $output->writeln('超级管理员账号admin的密码成功重置为uniadmin');
                    } else {
                        $output->writeln('重置失败');
                    }
                    break;
            }
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}
