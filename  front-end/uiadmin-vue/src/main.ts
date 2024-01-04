/**
 * +----------------------------------------------------------------------
 * | uiadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/
import { createApp } from 'vue'
import App from './App.vue'
import './index.css'
import { router } from './router'
import ElementPlus from 'element-plus'
import zhCn from 'element-plus/es/locale/lang/zh-cn'
import 'element-plus/dist/index.css'  //引入element-plus样式

const app = createApp(App)

import util from './libs/util';
app.config.globalProperties.$util = util;

app.use(router)

app.use(ElementPlus, {
  locale: zhCn,
})

app.mount('#app');

