<?php
// +----------------------------------------------------------------------
// | UiAdmin配置文件
// +----------------------------------------------------------------------

return [
    'site' => [
        // 系统信息
        'title' => "UiAdmin后台",     // 系统名称
        'slogan' => "",               // 口号
        'description' => "",          // 简介
        'icp' => "",                  // ICP备案号
        'logo' => "",                 // 系统logo方形
        'logoTitle' => "",            // 系统logo带标题
        'apiPrefix' => '/api',        // api接口通用前缀
    ],

    'alisms' => [
        'accessKeyId' => '',
        'accessKeySecret' => ''
    ],

    'alipay' => [
        'appid' => '',
        'privateKey' => '',
        'publicKey' => '',
        'aliPublicKey' => '',
        'isSandbox' => 0,
        'rate' => 0
    ],

    'user' => [
        'lists' => [
            [
                'id' => 1,
                'userKey' => 'uiadmin',
                'nickname' => 'admin',
                'username' => 'admin',
                'password' => 'uiadmin',
                'avatar' => '',
                'roles' => [],
            ]
        ],
        'driver' => 'uiadmin\\core\\service\\User'
    ],

    'menu' => [
        'driver' => 'uiadmin\\core\\service\\Menu'
    ],

    'upload' => [
        'defaultUploadMaxSize' => 512, // 上传大小限制
        'defaultUploadDriver' => 'default', // 前台默认上传驱动
    ],
    
    'xyadmin' => [
        'entry' => 'admin'
    ]
];
