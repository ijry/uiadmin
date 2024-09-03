<?php

namespace uiadmin\core\console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Publish extends Command
{
    /**
     * 命令名称及签名.
     *
     * @var string
     */
    protected $signature = 'uiadmin:publish';

    /**
     * 命令描述.
     *
     * @var string
     */
    protected $description = 'publish uiadmin';

    /**
     * 创建命令.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 执行命令.
     *
     * @param  \App\Support\DripEmailer  $drip
     * @return mixed
     */
    public function handle()
    {
        if (!file_exists(config_path() . '/menu.json')) {
            copy(__DIR__.'/../menu.json', config_path() . '/menu.json');
        }
    }
}

