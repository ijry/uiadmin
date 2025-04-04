from flask import jsonify

def jsonres(data):
    resp = jsonify(data)
    resp.headers.add("Access-Control-Allow-Origin", "*")
    resp.headers.add('Access-Control-Allow-Headers', "Authorization, Content-Type, CloudId, Eid")
    resp.headers.add('Access-Control-Allow-Methods', "GET, POST, PUT, DELETE, OPTIONS")
    print(resp.headers)
    return resp

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
