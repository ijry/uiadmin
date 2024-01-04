/**
 * +----------------------------------------------------------------------
 * | uiadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2022 http://uiadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/
import { ElMessage, ElMessageBox } from 'element-plus';
// import store from '../store';
// import i18n from '@/plugins/i18n.ts';
// const { t } = i18n.global;
import util from './base.js';
let vue = {
    // $store: store
}

// request
import request from './request.js';
util.request = request.request;

import {router} from '../router';

// 注销登录
util.logout = function() {
  ElMessageBox.confirm('确定要注销登录吗？', '提示', {
    confirmButtonText: '注销',
    confirmButtonClass: 'el-button--danger',
    cancelButtonText: '取消',
    type: 'warning'
    }).then(async () => {
        // 先清除本地token
        vue.$store.dispatch('user/setToken', '');
        vue.$store.dispatch('user/setUserInfo', '');
        // 清除服务器缓存
        let res = await util.request({
          url: '/v1/core/user/logout',
          method: 'delete',
        });
        if (res.code == 200) {
          util.showToast({
            title: '注销成功',
            icon: 'success',
            duration: 2000
          });
          // 刷新页面
          if (!import.meta.env.SSR) {
            window.location.href = this.$xyutil.config.frontDomain();
          }
        } else {
          util.showToast({
            title: res.msg,
            icon: 'loading',
            duration: 3000
          });
        }
    }).catch(() => {
    });
}

// 判断登录
// 可以用于在跳转某个页面前检测需要登录的话弹出登录页面
util.isLogin = function(showModal = true, redirect = false) {
    if (!util.getToken()) {
        if (redirect) {
            router.push({
                path: '/user/login'
            });
        }
        if (showModal) {
            util.showModalLogin();
        }
        return false;
    }
    return true;
}

 // 弹窗提醒
 util.showToast = function({
    title,
    icon = 'loading',
    duration = 3000
}) {
    if (icon == 'loading') {
        icon = 'warning';
    }
    ElMessage({
        message: title,
        type: icon,
    })
    return;
}

// 现在不支持自定义全局弹窗的内容，不得不先跳转到一个新页面实现。
util.showModalLogin = function() {
    ElMessageBox.confirm(
        '您尚未登录，点击确定跳转登录页面?',
        '提示',
        {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning',
        }
      )
        .then(() => {
            router.push({
                path: '/user/login'
            });
        })
        .catch(() => {
        });
}

// 获取token
util.getToken = function () {
    // return vue?.$store?.state?.user?.token;
};

// 存储token
util.setToken = function (data) {
    // return vue?.$store.commit('user/setToken', data);
};

util.runEnv = function (){
  var ua = window.navigator.userAgent.toLowerCase();
  // 是否在微信环境下
  if(ua.match(/MicroMessenger/i) == 'micromessenger' && ua.match(/wxwork/i) != 'wxwork'){
      return 'weixin-h5';
  }
  // 是否在企业微信环境下
  if(ua.match(/wxwork/i) == 'wxwork'){
      return 'wework-h5';
  }
  // 是否在utools环境下
  if(window.utools) {
      return 'utools';
  }
  return '';
}

util.showBrowerTip = function() {
  // 提示浏览器兼容
  var ua = navigator.userAgent.toLowerCase();  //获取用户端信息
  var uaInfo = {
      ie : /msie/ .test(ua) && !/opera/ .test(ua),  //匹配IE浏览器
      op : /opera/ .test(ua),  //匹配Opera浏览器
      sa : /version.*safari/.test(ua),  //匹配Safari浏览器
      ch : /chrome/.test(ua),  //匹配Chrome浏览器
      ff : /gecko/.test(ua) && !/webkit/.test(ua)  //匹配Firefox浏览器
  };
  if (uaInfo.ie || uaInfo.ff || uaInfo.op) {
      var list = document.getElementsByTagName('body')[0];
      var div = document.createElement("div");
      div.className = 'get-chrome-tip';
      div.style.cssText = 'box-sizing: border-box;opacity:0.9;background: #fff;display: block;z-index: 999;height: 60px;position: fixed;top:0;width: 100%;text-align: center;color: #333;padding-top: 20px;';
      var alink = document.createElement("a");
      alink.href="https://xiazai.zol.com.cn/detail/33/327560.shtml";
      alink.appendChild(document.createTextNode('您的浏览器似乎已经过时，请下载最新版Chrome获得更好的体验！'));
      div.appendChild(alink);
      var span = document.createElement("span");
      span.appendChild(document.createTextNode('X'));
      span.style.cssText = 'float: right;padding-right: 30px;cursor: pointer;'
      span.className = 'get-chrome-tip-close';
      div.appendChild(span);
      list.insertBefore(div, list.children[0]);
      document.querySelector(".get-chrome-tip-close").addEventListener("click", function(){
          document.querySelector(".get-chrome-tip").style.display = 'none';
      });
  }
}

export default util;
