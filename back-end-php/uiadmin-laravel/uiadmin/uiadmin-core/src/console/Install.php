<?php

namespace uiadmin\core\console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Install extends Command
{
    /**
     * 命令名称及签名.
     *
     * @var string
     */
    protected $signature = 'uiadmin:install';

    /**
     * 命令描述.
     *
     * @var string
     */
    protected $description = 'install or update uiadmin';

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
        if (!env('UIADMIN_INSTALL')) {
            Artisan::call('uiadmin:publish');
            echo "执行文件发布完成\n";
            Artisan::call('migrate');
            echo "执行数据库迁移完成\n";
            Artisan::call('migrate --seed');
            echo "执行数据库seeds迁移完成\n";
            file_put_contents(base_path() . '/.env', "\n\nUIADMIN_INSTALL = true\n", FILE_APPEND);
            echo "恭喜，UiAdmin-Laravel9安装完成！\n";
            echo "接下来你可以运行php artisan serve\n";
            echo "然后访问http://127.0.0.1:8000\n";
        } else {
            Artisan::call('uiadmin:publish');
            echo "执行文件发布完成\n";
            Artisan::call('migrate');
            echo "执行数据库迁移完成\n";
            Artisan::call('migrate --seed');
            echo "执行数据库seeds迁移完成\n";
            echo "恭喜，UiAdmin-Laravel9更新完成！\n";
            echo "接下来你可以运行php artisan serve\n";
            echo "然后访问http://127.0.0.1:8000\n";
        }
    }
}

