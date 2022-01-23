<?php
/**
 * +----------------------------------------------------------------------
 * | UiAdmin [ 渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <ijry@qq.com>
 * +----------------------------------------------------------------------
*/

/**
 * 安装配置
 *
 * @author jry <ijry@qq.com>
 */
return [
    // 导出配置
    'export' => [
        'table' => [
            'rowLimit' => [
                'core_config' => 0,
                'core_menu' => 0,
                'core_module' => 1,
                'core_identity' => 0,
                'core_role' => 3,
                'core_user' => 0,
                'core_job' => 0,
                'core_login' => 0,
                'core_user_log' => 0
            ]
        ]
    ]
];
