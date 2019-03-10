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
import App from './App.vue'
import router from './router'
import store from './store'
import i18n from './i18n'
import './plugins/axios'
import './plugins/iview.js'
import util from './libs/util';

import TreeTable from 'tree-table-vue'
Vue.use(TreeTable)

Vue.config.productionTip = false

// 配置
let settings = new Object()
settings.vue_app_is_demo = process.env.VUE_APP_IS_DEMO
Vue.prototype.settings = settings

new Vue({
  router,
  store,
  i18n,
  render: h => h(App)
}).$mount('#app')
