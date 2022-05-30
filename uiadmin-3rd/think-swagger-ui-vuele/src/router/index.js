import Vue from 'vue'
import Router from 'vue-router'
import routeConfig from './route.config'
import { isEmpty } from 'tennetcn-ui/lib/utils'
import swaggerService from '@/api/swagger'
import store from '@/store'

Vue.use(Router)

const createRouter = () => new Router({
  scrollBehavior: () => ({ y: 0 }),
  routes: routeConfig
})

const router = createRouter()

var isFirstLoad = true

router.afterEach((to, from) => {
  if (isFirstLoad) {
    firstLoad()
    isFirstLoad = false
  }
})

function firstLoad() {
  if (router.currentRoute.path !== '/') {
    const swaggerPath = window.sessionStorage.swaggerPath
    const theme = window.sessionStorage.theme
    store.commit('theme', theme)
    if (isEmpty(swaggerPath)) {
      router.push({ path: '/' })
    } else {
      swaggerService.reqAndResolveSwagger(swaggerPath).then(result => {
      })
    }
  }
}

export default router
