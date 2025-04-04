import os
import importlib.util
menus = []
menus.append({"title": "系统", "path": "/_system", "pmenu": "/default_root", "menuType": -1, "sortnum": 99, "icon": "xyicon-settings", "isHide": 0,"status": 1})
menus.append({"title": "开发工具", "path": "/dev", "pmenu": "/_system", "menuType": 0, "sortnum": 0, "isHide": 0,"status": 1})
menus.append({"title": "内容", "path": "/_content", "pmenu": "/default_root", "menuType": -1, "sortnum": 10, "icon": "xyicon-plane", "isHide": 0,"status": 1})
menus.append({"title": "内容管理", "path": "/content", "pmenu": "/_content", "menuType": 0, "sortnum": 0, "isHide": 0,"status": 1})

# 自动加载控制器
def load_controllers(app, base_path="/appext"):
    print('base_dir' + app.state.base_dir)
    for root, dirs, files in os.walk(app.state.base_dir + base_path):
        # 如果当前目录是某个模块下的controller/admin目录
        if os.path.basename(root) == 'controller' or os.path.basename(root) == 'admin':
            for file in files:
                if file.endswith(".py") and file[0].isupper():
                    # 构建模块名称，将斜杠替换为点，并去掉 .py 扩展名
                    module_path = os.path.join(root, file)
                    module_name = 'appext.' + module_path[len("appext/"):-3].replace(os.sep, ".")
                    spec = importlib.util.spec_from_file_location(module_name, module_path)
                    module = importlib.util.module_from_spec(spec)
                    spec.loader.exec_module(module)
                    
                    # 假设每个控制器模块都有一个名为 'router' 的属性
                    if hasattr(module, 'router'):
                        print(f"加载控制器：{os.path.basename(root)}")
                        app.include_router(module.router)
        else:
            print(f"{os.path.basename(root)}")
def jsonres(data):
    # resp.headers.add("Access-Control-Allow-Origin", "*")
    # resp.headers.add('Access-Control-Allow-Headers', "Authorization, Content-Type, CloudId, Eid")
    # resp.headers.add('Access-Control-Allow-Methods', "GET, POST, PUT, DELETE, OPTIONS")
    return data

def list2tree(data: list) -> list:
    # 转成ID为Key的字典
    mapping: dict = dict(zip([i['path'] for i in data], data))
 
    # 树容器
    container: list = []
 
    for d in data:
        # 如果找不到父级项，则是根节点
        parent: dict = mapping.get(d['pmenu'])
        if parent is None:
            container.append(d)
        else:
            children: list = parent.get('children')
            if not children:
                children = []
            children.append(d)
            parent.update({'children': children})
    return container
