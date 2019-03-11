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

import util from './util.js'
export default {
     state: {
        menu_list:[],
     },
     getters: {
        get_menu_list: state => {
            return state.menu_list
        }
     },
     mutations: {
        set_menu_list:(state,data)=>{
            state.menu_list = data
            util.setMenulistInLocalstorage(data)
        },
     },
     actions: { 
        setMenuList({commit},data){
            //通过解构赋值得到commit方法
            commit('set_menu_list', data)
        }
     }
}
