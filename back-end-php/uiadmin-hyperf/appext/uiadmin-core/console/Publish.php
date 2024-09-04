<?php

namespace uiadmin\core\console;

namespace uiadmin\core\console;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;

class Publish extends HyperfCommand
{
    /**
     * 命令名称及签名.
     *
     * @var string
     */
    protected ?string $name = 'uiadmin:publish';

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
     * 执行命令.
     *
     * @param  \App\Support\DripEmailer  $drip
     * @return mixed
     */
    public function handle()
    {
        $destination = '.' . '/migrations/';
        $destination2 = '.' . '/migrations/seeds/';
        if(!is_dir($destination)){
            mkdir($destination, 0755, true);
        }
        if(!is_dir($destination2)){
            mkdir($destination2, 0755, true);
        }

        // 遍历uiadmin模块下的数据库迁移文件
        $uiadminDir = __DIR__ . '/../../../';
        $list = $this->myScanDir($uiadminDir);
        foreach ($list as $key => $value) {
            $source = $value . '/database/migrations/';
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

            $source = $value . '/database/seeds/';
            if (!is_dir($source)) {
                continue;
            }
            // $source = __DIR__.'/../database/seeds/';
            $handle = dir($source);
            while($entry=$handle->read()) {   
                if(($entry!=".")&&($entry!="..")){   
                    if(is_file($source.$entry)){
                        copy($source.$entry, $destination2.$entry);
                    }
                }
            }
        }
    }
}

