# 简介
UiAdmin是一套JSON与API驱动的轻量级Vue3+element通用后台。这里是专门针对uniCloud开发的通用后台，旨在大幅度降低管理后台开发的工作量。
除了uniCloud版本，我们还支持传统语言php/java/nodejs/python等各种语言版本的UiAdmin。UiAdmin基于uni-id架构。

## 演示

[https://unicloud.demo.uiadmin.net/uiadmin-api/core/index/xyadmin](https://unicloud.demo.uiadmin.net/uiadmin-api/core/index/xyadmin)

账号admin密码123456

## 开发文档

[https://uiadmin.net/docs/unicloud](https://uiadmin.net/docs/unicloud)

## 下载地址

[https://ext.dcloud.net.cn/plugin?id=20103](https://ext.dcloud.net.cn/plugin?id=20103)

## 特性

* 【免编译】无需vite/webpack打包编译
* 【免前端部署】首创无需前端部署，不占用uniCloud的前端网页托管。
* 【页面Builder】通过列表(筛选/分页/列表/增删改查)/表单(几十种组件)/详情/TAB/混合等XYBuilder自动渲染页面
* 【API驱动】只需要编写API接口完成后台管理开发
* 【快速部署】因为无需前端页面编译及部署，仅需要部署云函数，所以几秒就能完成部署更新。
* 【前后端一体】用户端与管理后台可以同在一个项目中，因为UiAdmin不占用uniCloud的前端网页托管。

## 部署教程

### 创建云服务空间并绑定到当前应用

打开https://unicloud.dcloud.net.cn/

### 安装

从扩展市场安装uniadmin-core扩展

### 上传云函数

在uniCloud/cloudfunctions右击`上传所有云函数、公共模块及Actions`

### 配置数据库

在uni_modules/uiadmin-core/datebase右击点击`上传所有DB Schema`

### 配置云函数URL化

UiAdmin完全基于云函数路由化
1.在服务空间-云函数-【uiadmin-core】-详情页面-云函数URL化-设置URL的PATH部分点击编辑
填写path值为`/uiadmin-api/core`
2.在服务空间-云函数-【uiadmin-cms】-详情页面-云函数URL化-设置URL的PATH部分点击编辑
填写path值为`/uiadmin-api/cms`

### 绑定自定义域名

因为阿里云的限制，必须绑定自定义域名方可访问。假设自定义域名为https://demo.xyz.com,那么后台页面网址则为
https://demo.xyz.com/uiadmin-api/core/index/xyadmin

## 模块开发

推荐创建新模块开发业务，当然如果你的业务实在很简单，你也可以直接就在uiadmin-cms里面开发。
初次使用建议直接在uiadmin-cms中开发熟悉后再创建自己独立的业务模块。

### 创建新模块

在cloudfunctions上右击创建新模块，命名规范建议uiadmin-xxx，比如uiadmin-blog。

### 绑定公共模块

在uiadmin-xxx上右击，管理公共模块或者扩展库依赖，勾选uiadmin-common。

### 基础文件

index.js
```
// index.js (通常无需改动)
const uniID = require('uni-id-common');
const Router = require("uni-cloud-router").Router; // 引入 Router
const router = new Router(require("./config.js")); // 根据 config 初始化 Router

exports.main = async (event, context) => {
  // console.log(event, context)
  return router.serve(event, context); // 由 Router 接管云函数
};
```

config.js
```
// config.js
const { midAuth, midLog } = require('uiadmin-common') // 引入 auth 中间件

module.exports = {
  debug: true, // 调试模式时，将返回 stack 错误堆栈
  baseDir: __dirname, // 必选，应用根目录
  multiApp: false, // 开启多应用
  middleware: [
      [
        // 数组格式，第一个元素为中间件，第二个元素为中间件生效规则配置
        midAuth('xxx'), // 注册中间件，注意这里的xxx应当与uiadmin-xxx保持一致
        { enable: true, ignore: /\/admin\// }, // 配置当前中间件生效规则，该规则表示以`/login`结尾的路由不会执行 auth 中间件校验 token
      ],
	  [
		midLog('xxx') // 日志中间件,
		{ enable: true, match: /.*admin\/.*/ },
	  ]
    ],
};

```

### 开发后台功能

参考uiadmin-core在controller目录开发接口即可

### 菜单配置

开发好的接口配置菜单在模块下的uiadmin-core/config.js文件中配置menus数组，具体菜单配置尤其需要注意path、pmenu、apiPrefix等字段值的关系。

### 部署扩展

在uiadmin-xxx上右击上传部署。

### 配置云函数URL

同样需要配置云函数URL，这里需要注意path值为`/uiadmin-api/xxx`，xxx十分重要。

### 菜单生效

登录UiAdmin后台后点击右上角`清空缓存`即可看到新的菜单生效。




