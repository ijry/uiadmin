<?php

namespace uiadmin\core;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            // 合并到  config/autoload/dependencies.php 文件
            'dependencies' => [],
            // 合并到  config/autoload/annotations.php 文件
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            // 默认 Command 的定义，合并到 Hyperf\Contract\ConfigInterface 内，换个方式理解也就是与 config/autoload/commands.php 对应
            'commands' => [
                \uiadmin\core\console\Install::class
            ],
            // 与 commands 类似
            'listeners' => [
                \uiadmin\core\listener\EventListener::class, // 注册事件监听器
            ],
            'middlewares' => [
                'http' => [
                    // 数组内配置您的全局中间件，顺序根据该数组的顺序
                    \uiadmin\core\middleware\Middleware::class
                ],
            ],
            // 组件默认配置文件，即执行命令后会把 source 的对应的文件复制为 destination 对应的的文件
            'publish' => [
                [
                    'id' => 'menu',
                    'description' => 'menu', // 描述
                    'source' => __DIR__ . '/menu.json',  // 对应的配置文件路径
                    'destination' => BASE_PATH . '/config/autoload/menu.json', // 复制为这个路径下的该文件
                ],
            ],
            // 亦可继续定义其它配置，最终都会合并到与 ConfigInterface 对应的配置储存器中
        ];
    }
}
