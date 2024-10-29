/**
 * +----------------------------------------------------------------------
 * | uiadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/
import { ElMessage } from 'element-plus';
import util from './util';
import axios from '@/plugins/axios';
export default {
    request: function({
        method = "get",
        url,
        data = {},
        header = {},
        dataType = 'json',
        success,
        fail,
        complete,
        noAuth = false
    }) {
        // 请求
        let params = {};
        let contentType = 'application/json';
        if (method == 'get') {
            params = data;
            if (url.indexOf("?") >= 0) {
                let params2 =util.getUrlQuery(url);
                url = url.split('?')[0];
                params = Object.assign(params2, params);
            }
            // 注意get没有请求body部分
            contentType = 'application/x-www-form-urlencoded';
        }
        if (0 != url.indexOf("http")) {
            let baseUrl = util.baseUrl();
            // console.log(baseUrl)
            if (!baseUrl) {
                alert('缺少baseUrl');
                return false;
            }
            // 自动补齐/
            if (0 != url.indexOf("/")) {
                url = '/' + url;
            }
            if (baseUrl.indexOf("?") >= 0) { // 如果根接口存在?一般是代理等特殊情况
                url = encodeURIComponent(url);
            }
            baseUrl = baseUrl.replace(/(\/$)/g,"");
            url = baseUrl + url;
        }
        var _header = {
            'Content-Type': contentType
        };
        if (!noAuth) {
          _header.Authorization = util.getToken()
        }
        header = Object.assign(_header, header);
        return new Promise((resolve, reject) => {
            axios({
                method: method,
                url: url,
                data: data,
                params: params,
                headers: header
            }).then(function(ret) {
                // loading.close();
                if (!ret.hasOwnProperty('data')) {
                    let res = new Object();
                    res.code = 0;
                    res.msg = ret;
                    reject(res);
                }
                let res = ret.data;
                // console.log(res);
                if(res.code == 401){
                    util.setToken(''); // 清除token否则会陷入无法注销登陆的困境
                    ElMessage({
                        message: res.msg,
                        type: 'error',
                    });
                    return false;
                }
                if (!res.hasOwnProperty("data")) {
                    res.data = {};
                }
                // 弹窗类型
                if (res.code != 200 && res.data.alert) {
                    switch (res.data.alert) {
                        case 'toast':
                            ElMessage({
                                message: res.data.msg,
                                type: 'error',
                            });
                            break;
                        case 'modal':
                            ElMessage({
                                message: res.data.msg,
                                type: 'error',
                            });
                            break;
                    }
                    return false;
                }
                resolve(res);
            }).catch(async function(error) {
                // loading.close();
                if (!error.hasOwnProperty('response')) {
                    let ret = new Object();
                    ret.code = 0;
                    ret.msg = error;
                    reject(ret);
                }
                let res = error.response;
                console.log(error);
                // ThinkPHP异常json友好提示
                if (res.data && res.data.code != 200 && res.data.traces) {
                }
                // else {
                    res = new Object();
                    res.code = 0;
                    res.msg = error.message;
                    reject(res);
                    // throw res;
                // }
            });
        });
    }
};
