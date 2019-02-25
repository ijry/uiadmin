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
        'build'        => 'beta1_201902221237'
    ],

    // API接口文档
    'api_doc'     => [
        'v1' => [
            'user/login' => [
                'POST' => [
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
];
