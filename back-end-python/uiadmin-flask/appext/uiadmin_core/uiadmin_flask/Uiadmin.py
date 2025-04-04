from functools import wraps
from flask import current_app
from .CoreController import CoreController
from mergedeep import merge

class Uiadmin:
    # 构造函数
    def __init__(self, app=None):
        self.app = app
        if app is not None:
            self.init_app(app)

    # 初始化应用
    def init_app(self, app):
        print('init uiadmin')

        # 在 app 应用中存储所有扩展实例, 可验证扩展是否完成实例化
        app.extensions['uiadmin'] = self

        # 配置
        app.config.setdefault('UIADMIN_SYSTEM_MENUTREE', [])
        app.config.setdefault('UIADMIN_SITE_TITLE', "UiAdmin")
        app.config.setdefault('UIADMIN_SITE_LOGO', "")
        app.config.setdefault('UIADMIN_SITE_LOGO_TITLE', "")
        app.config.setdefault('UIADMIN_SITE_LOGO_BADGE', "")
        app.config.setdefault('UIADMIN_SYTE_VERSION', "1.0.0")

        # 路由
        core = CoreController(app)
        app.add_url_rule('/xyadmin/', view_func=core.xyadmin)
        app.add_url_rule('/xyadmin/api', view_func=core.xyadmin_api)
        app.add_url_rule('/api/v1/admin/user/login', view_func=core.admin_login, methods=['POST'])
        app.add_url_rule('/api/v1/admin/user/info', view_func=core.admin_user_info)
        app.add_url_rule('/api/v1/admin/menu/trees', view_func=core.admin_menu_trees)
        app.add_url_rule('/api/v1/admin/index/index', view_func=core.admin_index)
 
    # 接口方法装饰器，被装饰的接口将自动生成菜单。
    def menu_item(self, parameter):
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
        self.app.config['UIADMIN_SYSTEM_MENUTREE'].append(params)
        def decorator(func):
            @wraps(func)
            def inner(*args, **kwargs):
                ret = func(*args, **kwargs)
                return ret
            return inner
        return decorator
