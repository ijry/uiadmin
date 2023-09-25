[![Build Status](https://travis-ci.org/ijry/initadmin.svg?branch=master)](https://travis-ci.org/ijry/initadmin)
![Downloads](https://img.shields.io/badge/downloads-1K-brightgreen.svg)
![License](https://img.shields.io/badge/license-Apache2-brightgreen.svg)
![builder](https://img.shields.io/badge/xybuilder-1.2.0-brightgreen.svg)
![cloudadmin](https://img.shields.io/badge/xyadmin-1.2.0-brightgreen.svg)
![RepoSize](https://img.shields.io/github/repo-size/ijry/initadmin.svg)
![Star](https://img.shields.io/github/stars/ijry/initadmin.svg?style=social)
[![Open in Visual Studio Code](https://img.shields.io/badge/-open%20in%20vscode-blue?style=for-the-badge&logo=visualstudiocode)](https://open.vscode.dev/ijry/uiadmin)

本项目来源于[《腾讯云 Cloud Studio 实战训练营》](https://marketing.csdn.net/p/06a21ca7f4a1843512fa8f8c40a16635)的参赛作品，该作品在腾讯云 [Cloud Studio](https://www.cloudstudio.net/?utm=csdn) 中运行无误。

## 介绍
UiAdmin是一套渐进式模块化开源后台，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能；同时我们按照统一的API风格，开发了支持spring-boot3.0、thinkphp6.0、laravel9.0、Hyperf3.0版本的后端实现；后台管理界面前端采用vue+element实现。

## 预览

![UiAdmin](https://cdn1.jiangruyi.com/uniCloud2022/VKCEYUGU-f12e1180-fce8-465f-a4cd-9f2da88ca0e6/6ce3a522-6b27-47d9-abd1-5573620bc636.png)


## 为什么选择UiAdmin-降本增效

### UiAdmin开发对比传统开发


| 项目  | UiAdmin方式 | 传统方式 |
| ----------- | ----------- | ----------- |
| 后端开发人员数量    | 1       |1       |
| 前端开发人员数量  | 0       |1       |
| 功能开发速度  | 2.5X |1X      |
| BUG数量  | 粗估降低70%  | 存在 |
| 需要API文档  |不需要  | 需要 |
| 需要前后端联调  |不需要  | 需要 |
| 综合研发成本  | 降低50%  | - |
| 节省团队成本  | 预计节省20万元+/1年/1前端  | - |

注：

1、传统方式指的是现在绝大多数团队使用的前端画页面、后端写接口写接口文档、前后端联调开发方式

2、UiAdmin方式指的是使用Builder页面自动构建方式开发（非代码生成器）

3、节省成本计算方式为，前端以大约15K为基数，计算社保公积金、办公成本等。如果项目较大，传统方式开发需要不只一个前端人员时，节省成本将翻倍。

```
UiAdmin目前适合新项目采用，老项目暂时不建议除非重构。
```

### 降低企业&团队开发成本

UiAdmin设计并实现了了一种管理后台页面实时自动构建的技术方案，通过使用这种方案能够大幅度降低企业在后台管理上的开发成本，并且这种技术的学习使用非常简单，通过开发者熟知的lambda表达式链式调用即可，这种技术方案我们称之为页面自动构建Builder技术（非代码生成器）。

使用Builder可以让后端开发者不需要再写接口文档，不需要前后端联调，不需要配备一个前端开发者写重复性的增删改查页面。



### 技术主流

我们虽然使用了另一种后台开发技术方案，但是底层的技术架构却是紧跟主流的，比如最新的spring-boot3/thinkphp6/laravel9/hyperf3等都是支持的。完全不用担心团队的学习成本，如果您的团队需要，我们还提供现场教学付费服务。


## 特性

### 模块化
UiAdmin后台本着高内聚低耦合的原则， 模块作为UiAdmin的最小功能包可以共享 用户可以在模块市场上传下载模块

### Builder动态页面构建

UiAdmin首创自主研发了基于前后端分离的 页面自动生成技术，目前支持xyBuilderList和 xyBuilderForm，前者自动生成列表后者自动 生成表单，二者结合可以完成90%以上的 后台功能需求。

## 多平台支持

UiAdmin诞生在移动互联网后半场，面多各种 流量入口，UiAdmin将从如下方面对多个平台支持： pc端采用web方式实现，手机端将采用uni-app技术， 达到一次开发全面覆盖iOS、安卓、微信小程序、支 付宝小程序、百度小程序、头条小程序、H5，从而 节省开发者的大量精力。

### 多语言API兼容

UiAdmin后台将打造统一的后台框架体系， 后端横跨php、java、python、node、.net 等等语言，前端将支持vue、dart等语言框架，多个语言支持遵循统一的API风格.

## 仓库说明

这是一个Monorepo仓库，包含不同语言与框架的uiadmin实现。


### 后端实现Java版本（支持spring-boot3.0）

```
back-end-java/summer-boot 基于spring-boot3.0框架的uiadmin后端实现（开发文档：https://uiadmin.net/docs/summer)
```

### 后端实现PHP版本

```
back-end/uiadmin-thinkphp 基于thinkphp6框架的uiadmin后端实现（开发文档：https://uiadmin.net/docs/uiadmin1-2）

back-end/uiadmin-laravel 基于laravel9框架的uiadmin后端实现（开发文档：https://uiadmin.net/docs/lrvadmin）

back-end/uiadmin-hyperf 基于hyperf3框架的uiadmin后端实现（开发文档：https://uiadmin.net/docs/hyfadmin）


```


### 前端实现

```
front-end/uiadmin-uniapp uni-app版本前台用户端
front-end/uiadmin-vue 基于vue3+vite+typescript的前台脚手架工程
front-end/uiadmin-flutter Flutter版本前台用户端
```

### 其它

```
uiadmin-3rd 一些第三方的开源依赖项目，一般不需要自己编译。
```

##  安装
请参考文档

## 资源
官方网站：https://uiadmin.net  
成功案例：https://uiadmin.net/case  
插件市场：https://uiadmin.net/ext  
交流社区：https://uiadmin.net/ask  


## 开源地址
码云仓库：https://gitee.com/uiadmin  
github：https://github.com/ijry/uiadmin  

## 注意
如果需要1.0版本的ThinkPHP5.1版本请查看本仓库的1.0分支即可。

# QQ群
欢迎加群一起讨论框架选型、功能实现、架构等等  
QQ群：275346949  
点击链接加入群聊【QQ群1】：https://jq.qq.com/?_wv=1027&k=5sxKFMc

