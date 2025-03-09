const { Controller } = require("uni-cloud-router");
module.exports = class IndexController extends Controller {
  async index(event) {
  	const { ctx, service } = this;
  	ctx.headers = {'content-type': 'application/json'}
	let host = "https://" + event.event.headers.host
	let menuTree = []
  	ctx.body = {
  		"code": 200,
  		"msg": "success",
  		"data": {
			"dataList": [
			  {
					  "type": "count",
					  "content": [
						  {
							  "item": {
								  "bgColor": "#2db7f5",
								  "icon": "ivu-icon ivu-icon-md-contacts",
								  "title": ""
							  },
							  "current": {
								  "suffix": "",
								  "value": "990"
							  },
							  "content": {
								  "value": "注册用户"
							  }
						  },
						  {
							  "item": {
								  "bgColor": "#19be6b",
								  "icon": "ivu-icon ivu-icon-md-person-add",
								  "title": ""
							  },
							  "current": {
								  "suffix": "",
								  "value": "10"
							  },
							  "content": {
								  "value": "今日新增"
							  }
						  },
						  {
							  "item": {
								  "bgColor": "#ff9900",
								  "icon": "ivu-icon ivu-icon-md-clock",
								  "title": ""
							  },
							  "current": {
								  "suffix": "",
								  "value": "1800"
							  },
							  "content": {
								  "value": "总消费"
							  }
						  },
						  {
							  "item": {
								  "bgColor": "#ed4014",
								  "icon": "ivu-icon ivu-icon-ios-paper-plane",
								  "title": ""
							  },
							  "current": {
								  "suffix": "",
								  "value": "88"
							  },
							  "content": {
								  "value": "今日消费"
							  }
						  }
					  ],
					  "span": 24
			  },
			  {
					  "type": "card",
					  "title": "系统信息",
					  "content": [
						  {
							  "type": "text",
							  "title": "域名",
							  "value": host
						  },
						  {
							  "type": "text",
							  "title": "node版本",
							  "value": process.version
						  },
						  {
							  "type": "text",
							  "title": "服务器时间",
							  "value": new Date().toLocaleString()
						  },
						  {
							  "type": "text",
							  "title": "官方网站",
							  "value": "https://uiadmin.net(ijry@qq.com)"
						  },
						  {
							  "type": "text",
							  "title": "QQ交流群",
							  "value": "949222097"
						  }
					  ],
					  "span": 12
			  },
			  {
					  "type": "card",
					  "title": "项目信息",
					  "content": [
						  {
							  "type": "text",
							  "title": "项目名称",
							  "value": "uniCloud版本UiAdmin"
						  },
						  {
							  "type": "text",
							  "title": "项目口号",
							  "value": "轻量级免构建通用后台"
						  },
						  {
							  "type": "text",
							  "title": "项目简介",
							  "value": "UiAdmin是一套零前端代码通用后台，采用前后端分离技术，数据交互采用json格式；通过后端Builder不需要一行前端代码就能实时体验一个vue+element的现代化后台；一套兼容性的API标准，全面覆盖java/php/nodejs/python等多个后端语言和框架。"
						  },
						  {
							  "type": "text",
							  "title": "ICP备案号",
							  "value": "苏ICP备xxx号"
						  }
					  ],
					  "span": 12
			  }
		  ]
  		}
  	}
  }
};
