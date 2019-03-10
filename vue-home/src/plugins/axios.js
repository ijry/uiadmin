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

"use strict";

import Vue from 'vue';
import qs from 'qs'
import axios from "axios";
import util from '@/libs/util'

// Full config:  https://github.com/axios/axios#request-config
axios.defaults.headers.common['Authorization'] = 'Bearer ' + util.getToken();
const contentType = 'application/json'  // application/x-www-form-urlencoded或者application/json
axios.defaults.headers.post['Content-Type'] = contentType;
axios.defaults.transformRequest = data => {
  if (contentType == 'application/x-www-form-urlencoded') {
    return qs.stringify(data)
  } else {
    return data
  }
};

let config = {
  baseURL: process.env.VUE_APP_BASE_URL,
  timeout: 60 * 1000, // Timeout
  withCredentials: false, // Check cross-site Access-Control
};

const _axios = axios.create(config);

_axios.interceptors.request.use(
  function(config) {
    // Do something before request is sent
    return config;
  },
  function(error) {
    // Do something with request error
    return Promise.reject(error);
  }
);

// Add a response interceptor
_axios.interceptors.response.use(
  function(response) {
    // Do something with response data
    //window.console.log(response)
    if(response.data.data.need_login == 1){
      //window.console.log(router)
      router.push('/core/user/login')
    }
    return response;
  },
  function(error) {
    // Do something with response error
    return Promise.reject(error);
  }
);

Plugin.install = function(Vue, options) {
  Vue.axios = _axios;
  window.axios = _axios;
  Object.defineProperties(Vue.prototype, {
    axios: {
      get() {
        return _axios;
      }
    },
    $axios: {
      get() {
        return _axios;
      }
    },
  });
};

Vue.use(Plugin)

export default Plugin;
