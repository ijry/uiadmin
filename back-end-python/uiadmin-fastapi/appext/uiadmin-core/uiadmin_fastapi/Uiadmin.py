from functools import wraps
from fastapi import Request,APIRouter
from config2.config import config
from .CoreController import CoreController,router
from mergedeep import merge
from .utils import menus

class Uiadmin:
    # 构造函数
    def __init__(self, app = None):
        self.app = app
        self.menus = []
        if app is not None:
            self.init_app(app)

    # 初始化应用
    def init_app(self, app):
        print('init uiadmin-fastapi')
        app.include_router(router)
 
    # 接口方法装饰器，被装饰的接口将自动生成菜单。
    def menu_item(parameter):
        # print(parameter)
        param = {
            "title": '',
            "path": '',
            "pmenu": '',
            "tip": '',
            "menuLayer": 'admin',
            "menuType": 1,
            "routeType": 'form',
            "apiPrefix": 'v1',
            "apiSuffix": '',
            "apiParams": '',
            "apiMethod": 'GET',
            "apiExt": '',
            "isHide": 0,
            "status": 1,
            "sortnum": 0,
            "pathSuffix": '',
            "outUrl": ''
        }
        params = merge(param, parameter)
        menus.append(params)
        def decorator(func):
            @wraps(func)
            def inner(*args, **kwargs):
                ret = func(*args, **kwargs)
                return ret
            return inner
        return decorator
