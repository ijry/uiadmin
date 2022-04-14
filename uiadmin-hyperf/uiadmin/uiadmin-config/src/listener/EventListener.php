<?php
namespace uiadmin\config\listener;

use App\Event\UserRegistered;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use \Hyperf\Contract\ConfigInterface;
use Hyperf\HttpServer\Router\Router;
use uiadmin\config\model\Config as ConfigModel;

/**
 * @Listener 
 */
class EventListener implements ListenerInterface
{
    public function listen(): array
    {
        // 返回一个该监听器要监听的事件数组，可以同时监听多个事件
        return [
            \Hyperf\Framework\Event\OnManagerStart::class,
        ];
    }

    /**
     * @param UserRegistered $event
     */
    public function process(object $event) :void
    {
        if (env('UIADMIN_INSTALL')) {
            $config = new ConfigInterface();
            // 获取数据库中的配置覆盖配置文件的配置
            $dataList = ConfigModel::where('status', '=' , 1)
                ->where('application', '=' , 'uiadmin')
                ->where('profile', '=' , 'prod')
                ->where('label', '=' , 'main')
                ->get('value', 'name');
            foreach ($dataList as $name => $value) {
                $config->set($name, $value);
            }
        }
    }
}
