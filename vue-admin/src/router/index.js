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

import Vue from 'vue';
import Router from 'vue-router';
import routes from './routers';
import store from '@/store'
import iView from 'iview';
import util from '@/libs/util';
import util1 from '@/views/layout/store/util';

Vue.use(Router);

const router = new Router({
    routes,
    mode: 'hash'
});

const LOGIN_PAGE_NAME = '/core/user/login'

router.beforeEach((to, from, next) => {
  //判断是否存在登录成功返回的token
  const token = util.getToken()
  if(!token && to.name !== LOGIN_PAGE_NAME){
    next({
      name: LOGIN_PAGE_NAME // 跳转到登录页
    })
  }

  // 如果没有存储的左侧导航跳转到首页
  if (util1.getMenulistFromLocalstorage().length == 0 && to.name !== 'home' && to.name !== LOGIN_PAGE_NAME) {
    next({
        name: 'home' // 跳转首页
    })
  }
 
  //进度条
  iView.LoadingBar.start();
  util.title(to.meta.title);
  next();
})

router.afterEach((to, from, next) => {
  iView.LoadingBar.finish();
  window.scrollTo(0, 0);
})

export default router
