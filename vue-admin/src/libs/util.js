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

let util = {};

// 设置网页标题
util.title = function (title) {
    title = title ? title + '-InitAdmin' : 'InitAdmin管理后台';
    window.document.title = title;
};

// 获取token
util.getToken = function () {
    return localStorage.token
};

// 存储token
util.setToken = function (data) {
    return localStorage.token = data
};

export default util;
