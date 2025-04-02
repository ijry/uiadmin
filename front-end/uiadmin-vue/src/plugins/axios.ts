"use strict"
/**
 * +----------------------------------------------------------------------
 * | uiadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/
import qs from 'qs';
import axios from "axios";
import base from '../libs/base';
import router from '../router';

// 配置参考:  https://github.com/axios/axios#request-config
// axios.defaults.headers.common['CloudId'] = '0';  // 云应用ID
axios.defaults.transformRequest = (data, headers) => {
  if (!headers['Content-Type']) {
    return data;
  }
  if (headers['Content-Type'].indexOf('application/x-www-form-urlencoded') == 0) {
    return qs.stringify(data);
  } else if (headers['Content-Type'].indexOf('application/json') == 0) {
    return JSON.stringify(data);
  } else {
   return data;
 }
};

let config = {
  baseURL: base.baseUrl(),
  timeout: 60 * 1000000, // Timeout
  withCredentials: false, // Check cross-site Access-Control
};

const _axios = axios.create(config);

// 请求拦截器
_axios.interceptors.request.use(
  config => {
    return config;
  },
  error => {
    return Promise.reject(error);
  }
);

// 返回拦截器
_axios.interceptors.response.use(
  response => {
    // 预处理返回的数据
    if (response.data.code == 401 || response.data.code == 402) {
      router.push('/user/login');
    }
    return response;
  },
  error => {
    return Promise.reject(error);
  }
);

export default _axios;

