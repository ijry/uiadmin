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

import Vue from 'vue'
import Vuex from 'vuex'

import app from './module/app'
import user from './module/user'

import tagsview from '@/views/layout/store/tagsview' //多标签
import menu from '@/views/layout/store/menu' //左侧导航

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    //
  },
  mutations: {
    //
  },
  actions: {
    //
  },
  modules: {
    app,
    user,
    tagsview,
    menu
  }
})
