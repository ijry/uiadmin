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
    
    // 后台左侧功能一级分类，除了核心模块需要此配置，其它任何模块都不需要。
    'muenu_cate_top'  => [
        'system' => [
            'name' => 'system',
            'title' => '系统',
            'menu_type' => 'top',
            'show'  => 1,
            '_child' => [],
        ],
        'content' => [
            'name' => 'content',
            'title' => '内容',
            'menu_type' => 'top',
            'show'  => 1,
            '_child' => [],
        ],
        'order' => [
            'name' => 'order',
            'title' => '订单',
            'menu_type' => 'top',
            'show'  => 1,
            '_child' => [],
        ],
        'marketing' => [
            'name' => 'marketing',
            'title' => '营销',
            'menu_type' => 'top',
            'show'  => 1,
            '_child' => [],
        ]
    ],

    // 模块路由
    'route'     => array(
        // 资源路由
        'resource' => array(
            'api/core/menu' => 'core/menu',
            'api/core/user' => 'core/user',
            'api/core/jwt'  => 'core/jwt',
        )
    ),

    // API列表
    'api_list'     => [
        'core/jwt/save' => [
            'method' => 'post', // 请求方法
            'url' => 'core/jwt', // 接口地址
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
                'yes' => [
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
                'no' => [
                    'code' => 0,
                    'msg'  => '密码错误'
                ]
            ]
        ]
    ],
    
    // 后台左侧导航菜单列表
    'admin_menu'     => [
        'core' => [
            'name' => 'core',
            'title' => '系统',
            'menu_type' => 'cate', //导航类型有cate/page两种
            '_child' => [
                '/core/auth_role/list' => [
                    'title' => '权限管理',
                    'menu_type' => 'page',
                    '_child' => [
                        '/core/auth_role/add' => [
                            'title' => '添加用户组',
                        ],
                        '/core/auth_role/edit' => [
                            'title' => '修改用户组',
                        ],
                        '/core/auth_role/delete' => [
                            'title' => '删除用户组',
                        ]
                    ]
                ],
            ]
        ],
        '/core/user/list' => [
            'name' => '/core/user/list',
            'title' => '用户列表',
            'menu_type' => 'page',
            'route'   => '/core/user/list',
            '_child' => [
                '/core/user/add' => [
                    'title' => '添加用户',
                    'route'   => '/core/user/add',
                ],
                '/core/user/edit' => [
                    'title' => '修改用户信息',
                    'route'   => '/core/user/edit',
                ],
                '/core/user/delete' => [
                    'title' => '删除用户',
                    'route'   => '/core/user/delete',
                ]
            ]
        ],
    ]
];
