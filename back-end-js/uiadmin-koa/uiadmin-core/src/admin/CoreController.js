import {
  Controller,
  Delete,
  Get,
  Post
  } from '../decorator/router'
import {config} from '../util/common'
const {MenuItem, menuList} = require('../decorator/MenuItem')
const https = require('https')
const path = require('path')
const os = require('os')

var list2tree = function(list, parentMenuId) {
    let menuObj = {}
    list.forEach(item => {
        item.children = []
        menuObj[encodeURIComponent(item.path)] = item
    })
    return list.filter(item => {
        if (item.pmenu !== parentMenuId) {
            // console.log(encodeURIComponent(item.pmenu))
            // console.log(menuObj[encodeURIComponent(item.pmenu)])
            if (menuObj[encodeURIComponent(item.pmenu)]) {
                menuObj[encodeURIComponent(item.pmenu)].children.push(item)
                return false
            } else {
                return true
            }
        }
        return true
    })
}

function getAdmin() {
  return new Promise((resolve) => {
    https.get('https://uiadmin.net/xyadmin/?version=1.3.0', ret => {
      let list = [];
      ret.on('data', chunk => {
          list.push(chunk);
      });
      ret.on('end', () => {
        let html = Buffer.concat(list).toString()
        resolve(html)
      });
    }).on('error', err => {
      console.log('Error: ', err.message)
    })
  })
}

@Controller("")
class CoreController {

  @Get('/xyadmin/')
  async xyadmin(ctx) {
    let html = await getAdmin()
    ctx.body = html
  }

  @Get('/xyadmin')
  async home(ctx) {
    ctx.response.redirect('/xyadmin/')
  }

  @Get('/xyadmin/api')
  async xyadminApi(ctx) {
    let host = ctx.protocol + "://" + ctx.host
    ctx.body = {
        "code": 200,
        "msg": "success",
        "data": {
            "framework": "express",
            "stype": "应用",
            "name": "uiadmin-express",
            "api": {
                "apiLogin": "/v1/admin/user/login",
                "apiConfig": "/v1/site/info",
                "apiBase": host + "/api",
                "apiUserInfo": "/v1/admin/user/info",
                "apiAdmin": config.get("uiadmin.api-url.api-admin") || "/v1/admin/index/index",
                "apiMenuTrees": "/v1/admin/menu/trees"
            },
            "lang": "python",
            "title": config.get("uiadmin.site.title"),
            "domainRoot": host,
            "siteInfo": {
                "title": config.get("uiadmin.site.title")
            },
            "version": "1.0.0",
            "config": {
                "useVerify": "",
                // "headerRightToolbar": [
                //         {
                //             "type": "url",
                //             "title": "接口文档",
                //             "class": "xyicon xyicon-map",
                //             "url": "/doc.html"
                //         }
                // ]
            }
        }
    }
  }

  @Post('/api/v1/admin/user/login')
  async adminLogin(ctx) {
    // console.log(ctx.request.body)
    let req = ctx.request
    if (!req.body.account == 'admin'
        || !req.body.password == 'uiadmin') {
          ctx.body = {
            "code": 0,
            "msg": "账号或密码错误",
            "data": {
            }
        }
    }
    // console.log(config.get("uiadmin.user.user-list"))
    let ret = {}
    config.get("uiadmin.user.user-list").forEach(user => {
        if (user.username == req.body.account) {
            if (req.body.password == user.password) {
                ret = {
                    "code": 200,
                    "msg": "登录成功",
                    "data": {
                        "token": "eyJhbGciOiJIUzI1NiIsInR5cCI",
                        "userInfo": user
                    }
                }
            } else {
                ret = {
                    "code": 0,
                    "msg": "密码错误",
                    "data": {}
                }
            }
        } else {
            ret = {
                "code": 0,
                "msg": "用户不存在",
                "data": {}
            }
        }
    })
    ctx.body = ret
  }
  @Get('/api/v1/admin/user/info')
  async userInfo(ctx) {
    ctx.body = {
        "code": 200,
        "msg": "成功",
        "data": {
            "userInfo": {
                "id": 1,
                "nickname": "admin",
                "username": "admin",
                "country": "+86",
                "mobile": "",
                "email": "",
                "avatar": "",
                "roles": "",
                "authorities": ["ROLE_SUPER_ADMIN"],
                "status": 1,
            }
        }
    }
  }

  @MenuItem({title: "系统", path: "/_system", pmenu: "/default_root", menuType: -1, sortnum: 99, icon: "xyicon-settings"})
  @MenuItem({title: "开发工具", path: "/dev", pmenu: "/_system", menuType: 0, sortnum: 0})
  @MenuItem({title: "内容", path: "/_content", pmenu: "/default_root", menuType: -1, sortnum: 10, icon: "xyicon-plane"})
  @MenuItem({title: "内容管理", path: "/content", pmenu: "/_content", menuType: 0, sortnum: 0})
  @Get('/api/v1/admin/menu/trees')
  async adminMenuTrees(ctx) {
    let menuTree = list2tree(menuList, null)
    ctx.body = {
        "code": 200,
        "msg": "成功",
        "data": {
            "menu2routes": true,
            "listData": {
                "dataList": [
                    {
                        "title": config.get('uiadmin.site.title'),
                        "logo": "",
                        "path": "/root",
                        "status": 1,
                        "children": menuTree
                    }
                ]
            }
        }
    }
  }

  @Get('/api/v1/admin/index/index')
  async index(ctx) {
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
                                  "value": "0"
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
                                  "value": "0"
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
                                  "value": "0"
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
                                  "value": "0"
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
                              "title": "服务器IP",
                              "value": ""
                          },
                          {
                              "type": "text",
                              "title": "WEB服务器",
                              "value": ""
                          },
                          {
                              "type": "text",
                              "title": "JDK版本",
                              "value": ""
                          },
                          {
                              "type": "text",
                              "title": "服务器时间",
                              "value": ""
                          },
                          {
                              "type": "text",
                              "title": "官方网站",
                              "value": "https://jiangruyi.com(ijry@qq.com)"
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
                              "value": ""
                          },
                          {
                              "type": "text",
                              "title": "项目口号",
                              "value": ""
                          },
                          {
                              "type": "text",
                              "title": "项目简介",
                              "value": ""
                          },
                          {
                              "type": "text",
                              "title": "ICP备案号",
                              "value": ""
                          }
                      ],
                      "span": 12
              }
          ]
      }
    }
  }

  @Delete('/api/v1/core/user/logout')
  async logout(ctx) {
    ctx.bodyt = {
      code: 200,
      msg: "成功",
      data: {}
    }
  }
}

module.exports = CoreController
