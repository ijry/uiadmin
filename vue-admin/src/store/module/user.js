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

import util from '@/libs/util';
export default {
    state: {
      token: util.getToken(),
      user_info: {
        uid: '',
        nickname: '',
        avatar: '',
        role: []
      }
    },
    getters: {
    },
    mutations: {
      set_token:(state, data)=>{
        state.token = data
        util.setToken(data)
      },
      set_user_info:(state, data)=>{
        state.user_info = data
      }
    },
    actions: { 
      setToken({commit}, data){
        commit('set_token', data)
      },
      setUserInfo({commit},data){
        commit('set_user_info', data)
      },
    }
  }
    