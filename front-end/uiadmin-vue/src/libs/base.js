/**
 * +----------------------------------------------------------------------
 * | uiadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/

let util = {};

// 设置网页标题
util.title = function(title, site_title = '') {
    title = title ? title + '_' + site_title : site_title;
};

util.baseHost = function () {
    let host = '';
    host = import.meta.env.VITE_BASE_DOMAIN;
    return host;
};

// 前后端分离式部署时前端域名
util.frontDomain = function () {
    if (!import.meta.env.SSR) {
        return window.location.protocol + "//" + window.location.host;
    }
    return '';
};

// 当前网址
util.url = function () {
    if (!import.meta.env.SSR) {
        return window.location.href;
    }
    return '';
};

// baseDomain接口域名
util.baseDomain = function () {
    if (!import.meta.env.SSR) {
        let host = util.baseHost();
        if (host.startsWith('http')) {
            return host;
        }
        return window.location.protocol + "//" + host;
    }
    return "";
};

// baseUrl接口域名加前缀
util.baseUrl = function() {
    return util.baseDomain() + '/api/';;
};

/**
 *
 * @param {string} key
 */
util.getQueryString = function(key) {
  var reg = new RegExp("(^|&)" + key + "=([^&]*)(&|$)", "i");
  var r = window.location.search.substr(1).match(reg);
  if (r != null) return unescape(r[2]);
  return null;
}

// 截取url中的数据
util.getUrlQuery = function(tempStr) {
    /**
    * tempStr 格式是http://域名/路由?key=value&key=value...
    */
    if (!tempStr) {
        if (!import.meta.env.SSR) {
            tempStr = window.location.href;
        } else {
            tempStr = '';
        }
    }
    /**
    * tempArr 是一个字符串数组 格式是["key=value", "key=value", ...]
    */
    let tempArr = tempStr.split('?')[1] ? tempStr.split('?')[1].split('&') : [];
    /**
    * returnArr 是要返回出去的数据对象 格式是 { key: value, key: value, ... }
    */
    let returnArr = {};
    tempArr.forEach(element => {
        returnArr[element.split('=')[0]] = element.split('=')[1];
    });
    return returnArr;
}

// 时间格式化
util.formatTime = function(time, fmt) {
    return  util.timeFormat(time, fmt);
}
util.timeFormat = function(time, fmt) {
    if (!time) {
        return '';
    }
    if (!fmt) {
        fmt = 'yyyy-MM-dd HH:mm';
    }
    let date = '';
    if (typeof(time) == 'object' ) {
        date = time;
    } else if (typeof(time) == 'number' ) {
        date = new Date(time * 1000);
    } else {
        return time;
    }
    if (/(y+)/.test(fmt)) {
        fmt = fmt.replace(RegExp.$1, (date.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    let o = {
        'M+': date.getMonth() + 1,
        'd+': date.getDate(),
        'H+': date.getHours(),
        'm+': date.getMinutes(),
        's+': date.getSeconds()
    };
    for (let k in o) {
        if (new RegExp(`(${k})`).test(fmt)) {
            let str = o[k] + '';
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? str : util.padLeftZero(str));
        }
    }
    return fmt;
};

util.padLeftZero = function(str) {
    return ('00' + str).substr(str.length);
};

util.middleAsterisk = function(str, a = 3, b = 4){
    if(null != str && str != undefined){
        let pat = eval('/' + '(.{' + a + '}).*(.{' + b + '})' + '/');
            return str.replace(pat,'$1' + new Array(str.length - a - b).join('*') + '$2');
    } else {
        return "";
    }
}

util.treeSearch = function (tree, id) {
    var stark = [];
    stark = stark.concat(tree);
    while(stark.length) {
        var temp = stark.shift();
        if(temp.id == id) {
            return temp;
        } else {
            if(temp.children) {
                stark = stark.concat(temp.children);
            }
        }
    }
}

export default util;
