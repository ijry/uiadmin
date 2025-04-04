import requests
from starlette.responses import HTMLResponse
from urllib.parse import urlparse, parse_qs, parse_qsl
from fastapi import Request,APIRouter
from config2.config import config
from .utils import jsonres,list2tree,menus

router = APIRouter()

class CoreController:
    # 构造函数
    def __init__(self, app=None, params = {}):
      # self.app = current_app()
      global app_current
      app_current = app
      self.params = params
      # config = {}
      self.app = app

    @router.get("/xyadmin/", response_class=HTMLResponse)
    def xyadmin():
        url = "https://uiadmin.net/xyadmin/?version=1.3.0"
        res = requests.get(url)
        return res.text

    @router.get("/xyadmin/api")
    def xyadmin_api(request: Request):
        # 返回json数据的方法
        url = urlparse(str(request.url))
        domain = url.scheme + "://" + url.netloc
        data = {
            "code": 200,
            "msg": "success",
            "data": {
                "framework": "flask",
                "stype": "应用",
                "name": "uiadmin",
                "api": {
                    "apiLogin": "/v1/admin/user/login",
                    "apiConfig": "/v1/site/info",
                    "apiBase": domain + "/api",
                    "apiUserInfo": "/v1/admin/user/info",
                    "apiAdmin": "/v1/admin/index/index",
                    "apiMenuTrees": "/v1/admin/menu/trees"
                },
                "lang": "python",
                "title": config.uiadmin.site.title,
                "domainRoot": domain,
                "siteInfo": {
                    "title": config.uiadmin.site.title
                },
                "version": "1.0.0",
                "config": {
                    "useVerify": "",
                    # "headerRightToolbar": [
                    #         {
                    #             "type": "url",
                    #             "title": "接口文档",
                    #             "class": "xyicon xyicon-map",
                    #             "url": "/doc.html"
                    #         }
                    # ]
                }
            }
        }
        return jsonres(data)
    
    @router.post("/api/v1/admin/user/login")
    def admin_login():
        # 返回json数据的方法
        data = {
            "code": 200,
            "msg": "登录成功",
            "data": {
                "token": "eyJhbGciOiJIUzI1NiIsInR5cCI",
                "userInfo": {
                    "id": 1,
                    "nickname": "admin",
                    "username": "admin",
                    "avatar": "",
                    "roles": "",
                    "status": 1,
                    "authorities": ["ROLE_SUPER_ADMIN"]
                }
            }
        }
        return jsonres(data)

    @router.get("/api/v1/admin/user/info")
    def admin_user_info():
        # 返回json数据的方法
        data = {
            "code": 200,
            "msg": "成功",
            "data": {
                "userInfo": {
                    "id": 1,
                    "nickname": "admin",
                    "username": "admin",
                    "avatar": "",
                    "roles": "",
                    "status": 1,
                    "authorities": ["ROLE_SUPER_ADMIN"]
                }
            }
        }
        return jsonres(data)
    
    @router.get("/api/v1/admin/menu/trees")
    def admin_menu_trees():
      menuTree = list2tree(menus)
      # print(menuTree)
      # 返回json数据的方法
      data = {
          "code": 200,
          "msg": "登录成功",
          "data": {
              "menu2routes": True,
              "listData": {
                  "dataList": [
                      {
                          "title": config.uiadmin.site.title,
                          "logo": config.uiadmin.site.logo,
                          "path": "/root",
                          "status": 1,
                          "children": menuTree
                      }
                  ]
              }
          }
      }
      return jsonres(data)

    @router.get("/api/v1/admin/index/index")
    def admin_index():
        data = {
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
        return jsonres(data)
 