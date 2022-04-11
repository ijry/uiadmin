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
            Artisan::command('uiadmin:publish', function () {
                $this->info("执行文件发布完成");
            });
            Artisan::command('migrate', function () {
                $this->info("执行文件发布完成");
            });
            Artisan::command('migrate -seed', function () {
                $this->info("执行文件发布完成");
            });
            file_put_contents(root_path() . '.env', "\n\nUIADMIN_INSTALL = true\n", FILE_APPEND);
            echo "恭喜，UiAdmin-Laravel安装完成！\n";
        } else {
            Artisan::command('uiadmin:publish', function () {
                $this->info("执行文件发布完成");
            });
            Artisan::command('migrate', function () {
                $this->info("执行文件发布完成");
            });
            Artisan::command('migrate -seed', function () {
                $this->info("执行文件发布完成");
            });
            echo "恭喜，UiAdmin-Laravel更新完成！\n";
        }
    }
}

