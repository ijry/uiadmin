<?php

return [
    /*
     *Default Tauthz enforcer
     */
    'default' => 'basic',

    'log' => [
        // changes whether Lauthz will log messages to the Logger.
        'enabled' => false,
        // Casbin Logger, Supported: \Psr\Log\LoggerInterface|string
        'logger' => 'log',
    ],

    'enforcers' => [
        'basic' => [
            /*
            * Model 设置
            */
            'model' => [
                // 可选值: "file", "text"
                'config_type' => 'file',
                'config_file_path' => config_path().'tauthz-rbac-model.conf',
                'config_text' => '',
            ],

            // 适配器 .
            'adapter' => tauthz\adapter\DatabaseAdapter::class,

            /*
            * 数据库设置.
            */
            'database' => [
                // 数据库连接名称，不填为默认配置.
                'connection' => '',
                // 策略表名（不含表前缀）
                'rules_name' => 'rules',
                // 策略表完整名称.
                'rules_table' => null,
            ],
        ],
    ],
];
