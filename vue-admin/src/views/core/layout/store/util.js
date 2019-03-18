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

let util = {};

/**
 * @description 本地存储和获取标签导航列表
 */
util.setTagviewsInLocalstorage = list => {
    localStorage.visitedviews = JSON.stringify(list)
}
/**
 * @returns {Array} 其中的每个元素只包含路由原信息中的name, path, meta三项
 */
util.getTagviewsFromLocalstorage = () => {
    const list = localStorage.visitedviews
    return list ? JSON.parse(list) : [{
            name: 'home',
            path: '/home',
            title: '首页'
        }  
    ]
}

/**
 * @description 本地存储和获取左侧导航列表
 */
util.setMenulistInLocalstorage = list => {
    localStorage.menulist = JSON.stringify(list)
}
util.getMenulistFromLocalstorage = () => {
    const list = localStorage.menulist
    return list ? JSON.parse(list) : []
}

export default util;
