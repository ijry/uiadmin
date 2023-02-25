menus = []
menus.append({"title": "系统", "path": "/_system", "pmenu": "/default_root", "menuType": -1, "sortnum": 99, "icon": "xyicon-settings", "isHide": 0,"status": 1})
menus.append({"title": "开发工具", "path": "/dev", "pmenu": "/_system", "menuType": 0, "sortnum": 0, "isHide": 0,"status": 1})
menus.append({"title": "内容", "path": "/_content", "pmenu": "/default_root", "menuType": -1, "sortnum": 10, "icon": "xyicon-plane", "isHide": 0,"status": 1})
menus.append({"title": "内容管理", "path": "/content", "pmenu": "/_content", "menuType": 0, "sortnum": 0, "isHide": 0,"status": 1})

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
