/**
 * +----------------------------------------------------------------------
 * | InitAdmin/vue-admin [ InitAdmin渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2019 http://initadmin.net All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <ijry@qq.com>
 * +----------------------------------------------------------------------
*/

// 此文件用于汇总每个模块下的路由

var routers = [
    {
        path: '/',
        name: 'home',
        meta: {
          title: '首页'
        },
        component: () => import('@/views/home.vue')
    }
];

// 导入核心模块路由
import core from '@/views/module/core/route.js'
routers = routers.concat(core);

// 导入其它模块路由

export default routers;
