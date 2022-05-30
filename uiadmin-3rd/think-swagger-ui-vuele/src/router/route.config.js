import login from '@/views/pages/login'
import layoutMain from '@/views/pages/layout'
import simpleLayout from '@/views/pages/simpleLayout'
import main from '@/views/pages/main'
import simpleMain from '@/views/pages/simpleMain'
import swagger from '@/views/pages/swagger'
import simpleSwagger from '@/views/pages/simpleSwagger'

const mainRoute = [
  {
    path: '/',
    name: 'login',
    component: login
  },
  {
    path: '/main',
    name: 'main',
    component: layoutMain,
    children: [
      {
        path: 'index',
        name: 'mainIndex',
        component: main
      }
    ]
  },
  {
    path: '/simpleMain',
    name: 'simpleMain',
    component: simpleLayout,
    children: [
      {
        path: 'index',
        name: 'simpleMainIndex',
        component: simpleMain
      }
    ]
  },
  {
    path: '/swagger',
    name: 'swagger',
    component: layoutMain,
    children: [
      {
        path: 'index',
        name: 'swaggerIndex',
        component: swagger
      }
    ]
  },
  {
    path: '/simpleSwagger',
    name: 'simpleSwagger',
    component: simpleLayout,
    children: [
      {
        path: 'index',
        name: 'simpleSwaggerIndex',
        component: simpleSwagger
      }
    ]
  }
]

const megreRoute = function() {
  let route = []
  route = route.concat(mainRoute)
  return route
}

export default megreRoute()
