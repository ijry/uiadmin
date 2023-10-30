<?php

namespace uiadmin\auth;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            // 合并到  config/autoload/dependencies.php 文件
            'dependencies' => [], // 绑定到容器
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
            ],
            // 与 commands 类似
            'listeners' => [
                \uiadmin\auth\listener\EventListener::class, // 注册事件监听器
            ],
            // 组件默认配置文件，即执行命令后会把 source 的对应的文件复制为 destination 对应的的文件
            'publish' => [
                // [
                //     'id' => 'db',
                //     'description' => 'db', // 描述
                //     // 建议默认配置放在 publish 文件夹中，文件命名和组件名称相同
                //     'source' => __DIR__ . '/../database/migrations',  // 对应的配置文件路径
                //     'destination' => BASE_PATH . '/migrations/', // 复制为这个路径下的该文件
                // ]
            ],
            // 亦可继续定义其它配置，最终都会合并到与 ConfigInterface 对应的配置储存器中
        ];
    }
}
