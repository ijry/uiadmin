<?php
// +----------------------------------------------------------------------
// | UniAdmin配置文件
// +----------------------------------------------------------------------

return [
    // 系统信息
    'name' => "UniAdmin后台",      // 系统名称
    'logo' => "",                 // 系统logo方形
    'logoTitle' => "",            // 系统logo带标题
    'apiPrefix' => '/api',        // api接口通用前缀

    'upload' => [
        'defaultUploadMaxSize' => 512, // 上传大小限制
        'defaultUploadDriver' => 'default', // 前台默认上传驱动
    ],
    
    'xyadmin' => [
        'version' => '1.2.0'
    ]
];
