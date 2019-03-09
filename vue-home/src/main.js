import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import i18n from './i18n'
import './plugins/axios'
import './plugins/iview.js'
import util from './libs/util';

import VueParticles from 'vue-particles'  
Vue.use(VueParticles)  

Vue.config.productionTip = false

new Vue({
  router,
  store,
  i18n,
  render: h => h(App)
}).$mount('#app')
