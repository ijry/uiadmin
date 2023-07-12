import requests
from flask import current_app,request
from .utils import jsonres,list2tree

class CoreController:
    # 构造函数
    def __init__(self, app=None):
      # self.app = current_app()
      self.app = app

    # @self.app.route("/xyadmin/")
    def xyadmin(self):
        url = "https://uiadmin.net/xyadmin/?version=1.3.0"
        res = requests.get(url)
        return res.text

    # @app.route("/xyadmin/api")
    def xyadmin_api(self):
        # 返回json数据的方法
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
                    "apiBase": request.scheme + "://" + request.host + "/api",
                    "apiUserInfo": "/v1/admin/user/info",
                    "apiAdmin": "/v1/admin/index/index",
                    "apiMenuTrees": "/v1/admin/menu/trees"
                },
                "lang": "python",
                "title": self.app.config['UIADMIN_SITE_TITLE'],
                "domainRoot": request.scheme + "://" + request.host,
                "siteInfo": {
                    "title": self.app.config['UIADMIN_SITE_TITLE']
                },
                "version": self.app.config['UIADMIN_SYTE_VERSION'],
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
    
    # @app.route("/api/v1/admin/user/login", methods=["post"])
    def admin_login(self):
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

    # @app.route("/api/v1/admin/user/info")
    def admin_user_info(self):
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
    
    # @app.route("/api/v1/admin/menu/trees")
    def admin_menu_trees(self):
      self.app.config['UIADMIN_SYSTEM_MENUTREE'].append({"title": "系统", "path": "/_system", "pmenu": "/default_root", "menuType": -1, "sortnum": 99, "icon": "xyicon-settings", "isHide": 0,"status": 1})
      self.app.config['UIADMIN_SYSTEM_MENUTREE'].append({"title": "开发工具", "path": "/dev", "pmenu": "/_system", "menuType": 0, "sortnum": 0, "isHide": 0,"status": 1})
      self.app.config['UIADMIN_SYSTEM_MENUTREE'].append({"title": "内容", "path": "/_content", "pmenu": "/default_root", "menuType": -1, "sortnum": 10, "icon": "xyicon-plane", "isHide": 0,"status": 1})
      self.app.config['UIADMIN_SYSTEM_MENUTREE'].append({"title": "内容管理", "path": "/content", "pmenu": "/_content", "menuType": 0, "sortnum": 0, "isHide": 0,"status": 1})
      menuList = self.app.config['UIADMIN_SYSTEM_MENUTREE']
      menuTree = list2tree(menuList)
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
                          "title": self.app.config['UIADMIN_SITE_TITLE'],
                          "logo": "",
                          "path": "/root",
                          "status": 1,
                          "children": menuTree
                      }
                  ]
              }
          }
      }
      return jsonres(data)

    # @app.route("/api/v1/admin/index/index")
    def admin_index(self):
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
 