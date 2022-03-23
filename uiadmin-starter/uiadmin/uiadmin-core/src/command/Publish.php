<?php

namespace uiadmin\core\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

/**
 * 发布配置文件、迁移文件指令
 */
class Publish extends Command
{
    /**
     * 配置指令
     */
    protected function configure()
    {
        $this->setName('uiadmin:publish')->setDescription('Publish uiadmin');
    }

    private function myScanDir($dir) {
        $file_arr = scandir($dir);
        $new_arr = [];
        foreach($file_arr as $item){
            if($item!=".." && $item !="."){
                if(is_dir($dir."/".$item)){
                    $new_arr[] = $dir."/".$item;
                }else{
                    // $new_arr[] = $item;
                }
            }
        }
        return $new_arr;
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
        $destination = $this->app->getRootPath() . '/database/migrations/';
        if(!is_dir($destination)){
            mkdir($destination, 0755, true);
        }

        // 遍历uiadmin模块下的数据库迁移文件
        $uiadminDir = __DIR__ . '/../../../';
        $list = $this->myScanDir($uiadminDir);
        foreach ($list as $key => $value) {
            $source = $value . '/src/database/migrations/';
            if (!is_dir($source)) {
                continue;
            }
            // $source = __DIR__.'/../database/migrations/';
            $handle = dir($source);
            while($entry=$handle->read()) {   
                if(($entry!=".")&&($entry!="..")){   
                    if(is_file($source.$entry)){
                        copy($source.$entry, $destination.$entry);
                    }
                }
            }
        }

        if (!file_exists(config_path().'menu.json')) {
            copy(__DIR__.'/../menu.json', config_path().'menu.json');
        }
    }
}

