<?php
// +----------------------------------------------------------------------
// | UniAdmin配置文件
// +----------------------------------------------------------------------

return [
    'site' => [
        // 系统信息
        'title' => "UniAdmin后台",     // 系统名称
        'slogan' => "",               // 口号
        'description' => "",          // 简介
        'icp' => "",                  // ICP备案号
        'logo' => "",                 // 系统logo方形
        'logoTitle' => "",            // 系统logo带标题
        'apiPrefix' => '/api',        // api接口通用前缀
    ],

    'user' => [
        'lists' => [
            [
                'id' => 1,
                'nickname' => 'admin',
                'username' => 'admin',
                'password' => 'uniadmin',
                'avatar' => '',
                'roles' => [],
            ]
        ],
        'driver' => 'uniadmin\\core\\service\\User'
    ],

    'menu' => [
        'driver' => 'uniadmin\\core\\service\\Menu'
    ],

    'upload' => [
        'defaultUploadMaxSize' => 512, // 上传大小限制
        'defaultUploadDriver' => 'default', // 前台默认上传驱动
    ],
    
    'xyadmin' => [
    ]
];
