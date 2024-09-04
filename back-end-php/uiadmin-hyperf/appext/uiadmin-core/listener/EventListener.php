<?php
namespace uiadmin\core\listener;

use App\Event\UserRegistered;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Psr\Container\ContainerInterface;
use Hyperf\HttpServer\Router\Router;

/**
 * @Listener 
 */
class EventListener implements ListenerInterface
{
    private $container;

    public function __construct(ContainerInterface $container, )
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        // 返回一个该监听器要监听的事件数组，可以同时监听多个事件
        return [
            \Hyperf\Framework\Event\AfterWorkerStart::class
        ];
    }

    /**
     * @param UserRegistered $event
     */
    public function process(object $event) :void
    {
    }
}
