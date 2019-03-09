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

import Layout from '@/views/layout';
import util1 from '@/views/layout/store/util';
import store from '@/store'

var routers = [
    {
      path: '/',
      name: 'root',
      redirect: '/home',
      meta: {
        title: '首页'
      },
      component: Layout,
      children: [
        {
          path: '/home/',
          name: 'home',
          meta: {
            title: '首页'
          },
          component: () => import('@/views/home.vue')
        }
      ]
    }
];

let menu_data = util1.getMenulistFromLocalstorage()
if (typeof menu_data === 'object') {
    var children = [];
    store.dispatch('setMenuList', menu_data);
    for(let item in menu_data.menu_list) {
        switch (menu_data.menu_list[item].route_type) {
            case 'route':
                children.push(
                    {
                        path: menu_data.menu_list[item].path + menu_data.menu_list[item].api_suffix,
                        name: menu_data.menu_list[item].path,
                        meta: {
                            title: menu_data.menu_list[item].title
                        },
                        component: () => import('@/views/module' + menu_data.menu_list[item].path + '.vue')
                    }
                )
                break;
            default:
                children.push(
                    {
                        path: menu_data.menu_list[item].path + menu_data.menu_list[item].api_suffix,
                        name: menu_data.menu_list[item].path,
                        meta: {
                            dytype: 'dy' + menu_data.menu_list[item].route_type,
                            title: menu_data.menu_list[item].title,
                            api: menu_data.menu_list[item].api + menu_data.menu_list[item].api_params
                        },
                        component: () => import('@/views/components/va_dypage/va_dy' + menu_data.menu_list[item].route_type + '_route.vue')
                    }
                )
                break;
        }
    }
    routers[0].children = routers[0].children.concat(children)
}

// 导入tpvue核心模块路由
import core from '@/views/module/core/route.js'
routers = routers.concat(core.parent)
routers[0].children = routers[0].children.concat(core.children)

// 导入其它模块路由

export default routers;
