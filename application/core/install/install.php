<?php
/**
 * +----------------------------------------------------------------------
 * | InitAdmin/actionphp [ InitAdmin渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2019 http://initadmin.net All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <ijry@qq.com>
 * +----------------------------------------------------------------------
*/

/*
 * 模块信息配置
 */
return [
    // 模块信息
    'info'       => [
        'name'         => 'core',
        'title'        => '核心',
        'description'  => 'InitAdmin/actionphp核心模块',
        'developer'    => 'jry',
        'website'      => 'http://initadmin.net',
        'version'      => '0.1.0',
        'build'        => 'Alpha1_201902132200'
    ],

    // API接口
    'api'     => [
        'v1' => [
            // 规则路由
            'rule' => [
                'user/login' => [
                    'title' => '用户登陆',
                    'route' => 'User/login',
                    'method' => 'POST'
                ]
            ]
        ]
    ],

    // 路由API注释
    'api_doc'     => [
        'v1' => [
            'user/login' => [
                'POST' => [
                    'description' => '使用凭证登录系统', // 接口功能描述
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
