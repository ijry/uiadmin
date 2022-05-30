import request from '@/components/util/http/request'
import store from '@/store'
import { resolveMenu } from '@/router/menu.load'

function reqSwagger(url) {
  return new Promise(function(resolve, reject) {
    request.get(url, {}, response => {
      const result = response.data
      resolve(result)
    })
  })
}

function reqAndResolveSwagger(url) {
  return new Promise(function(resolve, reject) {
    reqSwagger(url).then(result => {
      if (result.openapi !== undefined) {
        window.sessionStorage.swaggerPath = url
        store.commit('swaggerPath', url)
        store.commit('swaggerInfo', result)

        const menus = resolveMenu()
        result['menus'] = menus
      }
      resolve(result)
    })
  })
}

function resolveSwagger(swaggerJson) {
  store.commit('swaggerPath', null)
  window.sessionStorage.swaggerPath = null
  const result = JSON.parse(swaggerJson)
  return new Promise(function(resolve, reject) {
    if (result.openapi !== undefined) {
      store.commit('swaggerInfo', result)

      const menus = resolveMenu()
      result['menus'] = menus
    }
    resolve(result)
  })
}

function sendRequest(method, url, requestData) {
  return new Promise(function(resolve, reject) {
    request[method](url, requestData, response => {
      resolve(response)
    })
  })
}

const swaggerService = {
  reqSwagger,
  resolveSwagger,
  reqAndResolveSwagger,
  sendRequest
}

export default swaggerService
