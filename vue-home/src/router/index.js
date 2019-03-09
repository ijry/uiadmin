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
import iView from 'iview';
import util from '@/libs/util';

Vue.use(Router);

const router = new Router({
  routes,
  mode: 'hash'
});

router.beforeEach((to, from, next) => {
  iView.LoadingBar.start();
  util.title(to.meta.title);
  next();
})

router.afterEach((to, from, next) => {
  iView.LoadingBar.finish();
  window.scrollTo(0, 0);
})

export default router
