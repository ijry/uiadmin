
const {MenuItem, menuList} = require('./decorator/MenuItem')
const CoreController = require('./admin/CoreController')
const XyBuilderList = require('./util/builder/XyBuilderList')
const XyBuilderForm = require('./util/builder/XyBuilderForm')
import {
    Controller,
    Get,
    Post,
    Put,
    Delete,
    controllers,
} from './decorator/router'
import {myconfig, config} from './util/common'
const { koaBody } = require('koa-body');

function uiadmin(app, router, myconfig = {}) {
    config.configs = myconfig
    controllers.forEach((item) => {
        // console.log(item)
        // 获取每个路由的前缀
        const prefix = item.constructor.prefix; 
        let url = item.url;
        if(prefix) url = `${prefix}${url}`; // 组合真正链接
        console.log(item.method,url); // 打印请求的路由method,url
        router[item.method](url, ...item.middleware, item.handler); // 创建路由
    });
    app.use(koaBody())
    app.use(router.routes()).use(router.allowedMethods()) // 路由装箱
}

module.exports = {
    uiadmin,
    config,
    Controller,
    Get,
    Post,
    Put,
    Delete,
    MenuItem,
    menuList,
    UiAdmin: CoreController,
    XyBuilderList,
    XyBuilderForm
}

