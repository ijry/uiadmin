import axios from 'axios'
import store from '@/store'
import { isEmpty } from 'tennetcn-ui/lib/utils'

axios.defaults.baseURL = ''
axios.defaults.headers.common['Authorization'] = window.sessionStorage.getItem('token')
axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*'
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8'
axios.defaults.withCredentials = false

const http = axios.create({
  transformRequest: [function(data) {
    let newData = ''
    for (const k in data) {
      if (data.hasOwnProperty(k) === true && data[k] !== null && data[k] !== undefined) {
        newData += encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) + '&'
      }
    }
    return newData
  }]
})
http.interceptors.request.use(setConfig)
const httpJson = axios.create({
  headers: {
    'Content-Type': 'application/json;charset=utf-8'
  }
})

httpJson.interceptors.request.use(setConfig)

function setConfig(config) {
  const headers = [].concat(store.state.swagger.headers || [])
  headers.forEach(item => {
    if (item.use && !isEmpty(item.headerInfo)) {
      config.headers[item.headerName] = item.headerInfo
    }
  })
  return config
}

function apiAxios(method, url, params, success, error) {
  execRequest(http({
    method: method,
    url: url,
    data: method === 'POST' || method === 'PUT' ? params : null,
    params: method === 'GET' || method === 'DELETE' ? params : null
  }), success, error)
}

function apiJsonAxios(method, url, params, success, error) {
  execRequest(httpJson({
    method: method,
    url: url,
    data: params
  }), success, error)
}

function execRequest(httpRequest, success, error) {
  httpRequest.then(function(res) {
    success(res)
  }).catch(function(err) {
    console.log(err, 'err')
    if (error || error === undefined) {
      success(err.response)
    } else {
      error(err.response)
    }
  })
}

export default {
  get: function(url, params, response) {
    return apiAxios('GET', url, params, response)
  },
  post: function(url, params, response) {
    return apiAxios('POST', url, params, response)
  },
  put: function(url, params, response) {
    return apiAxios('PUT', url, params, response)
  },
  delete: function(url, params, response) {
    return apiAxios('DELETE', url, params, response)
  },
  postJson: function(url, params, response) {
    return apiJsonAxios('POST', url, params, response)
  }
}
