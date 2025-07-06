import App from './App'
import 'uno.css';

// pinia
import pinia from '@/store'

// 引入全局uview-plus
import uviewPlus from '@/uni_modules/uview-plus'


// #ifdef VUE3
import { createSSRApp } from 'vue'
export function createApp() {
  const app = createSSRApp(App)
  app.use(pinia)
  app.use(uviewPlus)
  return {
    app
  }
}
// #endif