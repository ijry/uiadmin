// 获取无限级对象的值 
const getDeepVal = (key, data) => {
    return key.split('.').reduce((p, c) => {
      return p && p[c];
    }, data);
}

const config = {
    configs: {
        "uiadmin": {
            "site": {
                "title": "UiAdmin-koa",
                "logo": "",
                "logoTitle": "",
                "logoBadge": ""
            },
            "system": {
                "api-version": "1.0.0",
                "menu-from": "annotation"
            },
            "user": {
                "user-role": [
                    {
                        "id": 1,
                        "name": "super_admin",
                        "title": "超级管理员",
                        "menus": "",
                        "status": 1
                    },
                    {
                        "id": 2,
                        "name": "admin",
                        "title": "管理员",
                        "status": 1,
                        "menus": [
                            "/v1/admin/demo/lists"
                        ]
                    }
                ],
                "user-list": [
                    {
                        "id": 1,
                        "nickname": "admin",
                        "username": "admin",
                        "password": "uiadmin",
                        "avatar": "",
                        "roles": "super_admin",
                        "status": 1
                    }
                ]
            }
        }
    },
    get: function(key, defVal = '') {
        return getDeepVal(key, config.configs);
    }
}

module.exports = {
    config
}
