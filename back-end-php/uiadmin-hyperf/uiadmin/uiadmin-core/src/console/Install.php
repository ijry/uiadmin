<?php

namespace uiadmin\core\console;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;

class Install extends HyperfCommand
{
    /**
     * 执行的命令行
     *
     * @var string
     */
    protected ?string $name = 'uiadmin:install';

    /**
     * @var ContainerInterface
     */
    // protected $container;

    // public function __construct(ContainerInterface $container)
    // {
    //     $this->container = $container;

    //     parent::__construct('foo');
    // }

    // public function configure()
    // {
    //     parent::configure();
    //     $this->setDescription('install or update uiadmin');
    // }

    /**
     * 执行命令.
     *
     * @param  \App\Support\DripEmailer  $drip
     * @return mixed
     */
    public function handle()
    {
        if (!env('UIADMIN_INSTALL')) {
            $this->call('uiadmin:publish', [
            ]);
            echo "执行文件发布完成\n";
            $this->call('migrateh', [
            ]);
            echo "执行数据库迁移完成\n";
            $this->call('migrate', [
                '-seed' => ''
            ]);
            echo "执行数据库seeds迁移完成\n";
            file_put_contents(base_path() . '/.env', "\n\nUIADMIN_INSTALL = true\n", FILE_APPEND);
            echo "恭喜，UiAdmin-Hyperf安装完成！\n";
            echo "接下来你可以运行php bin/hyperf.php start\n";
            echo "然后访问http://127.0.0.1:9501\n";
        } else {
            $this->call('uiadmin:publish', [
            ]);
            echo "执行文件发布完成\n";
            $this->call('migrateh', [
            ]);
            echo "执行数据库迁移完成\n";
            $this->call('migrate', [
                '-seed' => ''
            ]);
            echo "执行数据库seeds迁移完成\n";
            echo "执行数据库seeds迁移完成\n";
            echo "恭喜，UiAdmin-Hyperf更新完成！\n";
            echo "接下来你可以运行php bin/hyperf.php start\n";
            echo "然后访问http://127.0.0.1:9501\n";
        }
    }
}

