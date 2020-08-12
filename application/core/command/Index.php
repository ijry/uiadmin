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
