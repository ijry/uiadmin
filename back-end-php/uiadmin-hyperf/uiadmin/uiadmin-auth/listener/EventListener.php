<?php
namespace uiadmin\auth\listener;

use App\Event\UserRegistered;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\HttpServer\Router\Router;

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
        // 安装auth扩展后设置驱动为auth
        // $uiadmin_config = config('uiadmin');
        // $uiadmin_config['user']['driver'] = 'uiadmin\\auth\\service\\User';
        // $uiadmin_config['menu']['driver'] = 'uiadmin\\auth\\service\\Menu';
        // config($uiadmin_config , 'uiadmin');
    }
}
