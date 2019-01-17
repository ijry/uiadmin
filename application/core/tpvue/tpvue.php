<?php
// +----------------------------------------------------------------------
// | tpvue [ 模块化渐进式后台 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2019 http://tpvue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------

/*
 * 模块信息配置
 */
return [
    // 模块信息
    'info'       => [
        'name'         => 'core',
        'title'        => '核心',
        'description'  => 'tpvue核心模块',
        'developer'    => 'jry',
        'website'      => 'http://tpvue.com',
        'version'      => '0.1.0',
        'build'        => 'Alpha1_2019010500'
    ],

    // 后台左侧导航菜单列表
    'admin_menu'     => [
        'core_0' => [
            'title' => '系统',
            'level' => '1', //导航层级最多3级
            '_child' => [
                '/core/auth_role/lists' => [
                    'title' => '权限管理',
                    'level' => '2',
                ],
                '/core/user/lists' => [
                    'title' => '用户列表',
                    'level' => '2',
                ]
            ]
        ]
    ],

    // 模块路由
    'route'     => [
        'v1' => [
            // 规则路由
            'rule' => [
                'admin/menu/lists' => ['admin.Menu/lists', 'GET'],
                'admin/auth_role/lists' => ['admin.AuthRole/lists', 'GET'],
                'admin/auth_role/add' => ['admin.AuthRole/add', 'POST'],
                'admin/auth_role/edit/:id' => ['admin.AuthRole/edit', 'PUT'],
                'admin/auth_role/delete/:id' => ['admin.AuthRole/delete', 'DELETE'],
                'admin/user/lists' => ['admin.User/lists', 'GET'],
                'admin/user/add' => ['admin.User/add', 'POST'],
                'admin/user/edit' => ['admin.User/edit', 'PUT'],
                'admin/user/delete/:id' => ['admin.User/delete', 'DELETE'],
                'user/login' => ['User/login']
            ]
        ]
    ],

    // 路由API注释
    'route_api'     => [
        'v1' => [
            'user/login' => [
                '0' => [
                    'method' => 'POST', // 请求方法
                    'title' => '用户登录', // 接口名称
                    'description' => '使用账号密码登录系统', // 接口功能描述
                    'params' => [
                        'account' => [
                            'is_must' => 1, //该参数是否必须
                            'title' => '账号', //字段名称
                            'description' => '账号支持手机号/邮箱/用户名', // 字段描述
                            'example' => 'test', // 字段传值示例
                        ],
                        'password' => [
                            'is_must' => 1, //该参数是否必须
                            'title' => '密码哈希', //字段名称
                            'description' => '密码必须在前台用公钥加密传输，这样系统跟安全，密码不容易在传输中被劫持。', // 字段描述
                            'example' => 'test123', // 字段传值示例
                        ]
                    ],
                    'return' => [
                        'success' => [
                            'code' => 200,
                            'msg'  => '登录成功',
                            'data'  => [
                                'token' => 'abcdefghijklmnabcdefghijklmn',
                                'user_info' => [
                                    'id' => 999,  // 用户的UID
                                    'username' => 'test',  // 用户名可以登录系统,不允许重复
                                    'nickname' => '测试账号',  // 用户昵称不可以登录系统，允许重复
                                    'avatar' => '头像地址',  // 用户的头像图片地址
                                ]
                            ]
                        ],
                        'error' => [
                            'code' => 0,
                            'msg'  => '密码错误'
                        ]
                    ]
                ]
            ]
        ]
    ]
];
