// config.js
const { midAuth, midLog } = require('uiadmin-common') // 引入 auth 中间件

const extName = 'core';
module.exports = {
  debug: true, // 调试模式时，将返回 stack 错误堆栈
  baseDir: __dirname, // 必选，应用根目录
  multiApp: false, // 开启多应用
  middleware: [
      [
        // 数组格式，第一个元素为中间件，第二个元素为中间件生效规则配置
        midAuth(extName), // 注册中间件
        { enable: true, match: /.*admin\/.*/ },
      ],
	  [
	    // 数组格式，第一个元素为中间件，第二个元素为中间件生效规则配置
	    midLog(extName), // 注册中间件
		{ enable: true, match: /.*admin\/.*/ },
	  ],
  ],
  sharedMenus: [
	  '/core/admin/index/index',
	  '/core/admin/menu/trees',
	  '/core/admin/user/info',
	  '/core/site/info'
  ],
  // 后台菜单
  menus: [
		{
			"title": 'UiAdmin',
			"logo": "",
			"path": "/root",
			"status": 1,
			"children": [
				{
					"title": "系统",
					"path": "/core",
					"pmenu": "/root",
					"menuType": "-1",
					"menuLayer": "admin",
					"status": 1,
					"children": [
						{
							"path": "/user",
							"pmenu": "/core",
							"menuType": "0",
							"title": "用户管理",
							"menuLayer": "admin",
							"apiPrefix": "core",
							"status": 1,
							"children": [
								{
									"title": "用户列表",
									"icon": "",
									"path": "/user/lists",
									"pmenu": "/user",
									"tip": "",
									"menuLayer": "admin",
									"menuType": 1,
									"routeType": "list",
									"apiPrefix": "core",
									"apiSuffix": "",
									"apiParams": "",
									"apiMethod": "GET",
									"apiExt": "",
									"doc": null,
									"isHide": 0,
									"status": 1,
									"sortnum": 0,
									"deleteTime": 0,
									"pathSuffix": ""
								},
								{
									"title": "角色列表",
									"icon": "",
									"path": "/role/lists",
									"pmenu": "/user",
									"tip": "",
									"menuLayer": "admin",
									"menuType": 1,
									"routeType": "list",
									"apiPrefix": "core",
									"apiSuffix": "",
									"apiParams": "",
									"apiMethod": "GET",
									"apiExt": "",
									"doc": null,
									"isHide": 0,
									"status": 1,
									"sortnum": 0,
									"deleteTime": 0,
									"pathSuffix": ""
								}
							]
						},
						{
							"path": "/system",
							"pmenu": "/core",
							"menuType": "0",
							"title": "系统管理",
							"menuLayer": "admin",
							"apiPrefix": "core",
							"status": 1,
							"children": [
								{
									"title": "后台日志",
									"icon": "",
									"path": "/log/lists",
									"pmenu": "/system",
									"tip": "",
									"menuLayer": "admin",
									"menuType": 1,
									"routeType": "list",
									"apiPrefix": "core",
									"apiSuffix": "",
									"apiParams": "",
									"apiMethod": "GET",
									"apiExt": "",
									"doc": null,
									"isHide": 0,
									"status": 1,
									"sortnum": 0,
									"deleteTime": 0,
									"pathSuffix": ""
								},
							]
						}
					]
				},
				{
					"title": "内容",
					"path": "/content",
					"pmenu": "/root",
					"menuType": "-1",
					"menuLayer": "admin",
					"status": 1,
					"children": [
						{
							"path": "/cms",
							"pmenu": "/content",
							"menuType": "0",
							"title": "内容管理",
							"menuLayer": "admin",
							"apiPrefix": "cms",
							"status": 1,
							"children": [
								{
									"title": "轮播列表",
									"icon": "",
									"path": "/banner/lists",
									"pmenu": "/cms",
									"tip": "",
									"menuLayer": "admin",
									"menuType": 1,
									"routeType": "list",
									"apiPrefix": "cms",
									"apiSuffix": "",
									"apiParams": "",
									"apiMethod": "GET",
									"apiExt": "",
									"doc": null,
									"isHide": 0,
									"status": 1,
									"sortnum": 0,
									"deleteTime": 0,
									"pathSuffix": ""
								},
								{
									"title": "文章列表",
									"icon": "",
									"path": "/article/lists",
									"pmenu": "/cms",
									"tip": "",
									"menuLayer": "admin",
									"menuType": 1,
									"routeType": "list",
									"apiPrefix": "cms",
									"apiSuffix": "",
									"apiParams": "",
									"apiMethod": "GET",
									"apiExt": "",
									"doc": null,
									"isHide": 0,
									"status": 1,
									"sortnum": 0,
									"deleteTime": 0,
									"pathSuffix": ""
								},
								{
									"title": "文章分类",
									"icon": "",
									"path": "/category/lists",
									"pmenu": "/cms",
									"tip": "",
									"menuLayer": "admin",
									"menuType": 1,
									"routeType": "list",
									"apiPrefix": "cms",
									"apiSuffix": "",
									"apiParams": "",
									"apiMethod": "GET",
									"apiExt": "",
									"doc": null,
									"isHide": 0,
									"status": 1,
									"sortnum": 0,
									"deleteTime": 0,
									"pathSuffix": ""
								},
							]
						}
					]
				},
				{
					"title": "商城",
					"path": "/shop",
					"pmenu": "/root",
					"menuType": "-1",
					"menuLayer": "admin",
					"status": 1,
					"children": [
						{
							"path": "/shopmg",
							"pmenu": "/shop",
							"menuType": "0",
							"title": "商品管理",
							"menuLayer": "admin",
							"apiPrefix": "shop",
							"status": 1,
							"children": [
								{
									"title": "商品列表",
									"icon": "",
									"path": "/goods/lists",
									"pmenu": "/shopmg",
									"tip": "",
									"menuLayer": "admin",
									"menuType": 1,
									"routeType": "list",
									"apiPrefix": "shop",
									"apiSuffix": "",
									"apiParams": "",
									"apiMethod": "GET",
									"apiExt": "",
									"doc": null,
									"isHide": 0,
									"status": 1,
									"sortnum": 0,
									"deleteTime": 0,
									"pathSuffix": ""
								}
							]
						}
					]
				}
			]
		}
	]
};
