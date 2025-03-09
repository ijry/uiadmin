const {
	Controller
} = require("uni-cloud-router");
const {
	XyBuilderList,
	XyBuilderForm,
	encryptPwd,
	treeToList
} = require("uiadmin-common");
const uniID = require('uni-id-common')
const db = uniCloud.database(); //代码块为cdb
const dbCmd = db.command // 取指令
const tableName = 'uni-id-roles';

module.exports = class UserController extends Controller {
	async lists(event, context) {
		const {
			ctx,
			service
		} = this;
		ctx.headers = {
			'content-type': 'application/json'
		}

		let res = await db.collection(tableName).orderBy("_id", "desc").get()
		let dataList = res.data

		let xyBuilderList = new XyBuilderList();
		xyBuilderList
			.init()
			.addColumn("_id", "序号", {
				type: "id"
			})
			.addColumn("role_id", "角色标识", {
				type: "text"
			})
			.addColumn("role_name", "角色名称", {
				type: "text",
			})
			.addColumn("comment", "备注", {
				type: "text"
			})
			// .addColumn("status", "状态", {
			// 	prefixType: "dot",
			// 	useOptions: true,
			// 	options: [{
			// 			title: "正常",
			// 			value: 0,
			// 			color: "green"
			// 		},
			// 		{
			// 			title: "禁用",
			// 			value: 1,
			// 			color: "red"
			// 		},
			// 	]
			// })
			.addColumn("create_date", "创建时间", {
				type: "time",
			})
			.addTopButton("add", "添加", {
				title: "新增",
				pageType: "modal",
				modalType: "form",
				api: "/core/admin/role/add",
				width: "800px"
			})
			.addColumn("rightButtonList", "操作", {
				type: "rightButtonList",
				minWidth: "120px"
			})
			.addRightButton("edit", "修改", {
				title: "修改",
				pageType: "modal",
				modalType: "form",
				api: "/core/admin/role/edit",
				apiSuffix: [],
				querySuffix: [
					['id', '_id']
				],
				width: "800px",
			})
			.setDataList(dataList)
		ctx.body = {
			"code": 200,
			"msg": "成功",
			"data": {
				listData: xyBuilderList.getData()
			}
		}
	}

	async add(event, context) {
		const {
			ctx,
			service
		} = this;
		ctx.headers = {
			'content-type': 'application/json'
		}
		
		// 菜单构造权限
		function addAdminAuthToMenu(menus) {  
		    // 遍历数组中的每个菜单项  
		    menus.forEach(val => {
				let apiPrefix = ''
				if (val['apiPrefix']) {
					apiPrefix = '/' + val['apiPrefix']
				}
		        val['adminAuth'] = apiPrefix + '/admin' + val['path'];
		  
		        // 如果当前菜单项有子菜单（children），则递归处理  
		        if (val.children && val.children.length > 0) {  
		            val.children = addAdminAuthToMenu(val.children);  
		        }  
		    });
			return menus;
		}  
		let dataList = JSON.parse(JSON.stringify(ctx.config.menus));
		let menuTree = addAdminAuthToMenu(dataList);

		let xyBuilderForm = new XyBuilderForm();
		xyBuilderForm
			.init()
			.addFormItem("role_id", "角色标识", "text", "", {})
			.addFormItem("role_name", "角色名称", "text", "", {})
			.addFormItem("comment", "备注", "text", "", {})
			.addFormItem("permission", "后台权限", "checkboxtree", [], {
				'tip': '勾选角色权限',
				'columns': [
				    {'title': '菜单(接口)', 'name': 'title', 'minWidth': '150px'},
				    {'title': '接口', 'name': 'adminAuth'},
				    {'title': '类型', 'name': 'menuType', 'width': '40px'},
				    {'title': '说明', 'name': 'tip'},
				],
				'data': menuTree,
				'nodeKey': 'adminAuth',
				'position': 'bottom'
			})
			.setConfig('submitApi', '/core/admin/role/doAdd')
		ctx.body = {
			"code": 200,
			"msg": "成功",
			"data": {
				formData: xyBuilderForm.getData()
			}
		}
	}

	async doAdd(event, context) {
		const {
			ctx,
			service
		} = this;
		ctx.headers = {
			'content-type': 'application/json'
		}
		
		// 查重复
		let exist = await db.collection(tableName).where({
			role_id: ctx.data.role_id
		}).get()
		if (exist.data.length && exist.data.length > 0) {
			ctx.body = {
				"code": 0,
				"msg": '角色标识已存在',
				"data": {}
			}
		}

		// 单条插入数据
		let res = await db.collection(tableName).add({
			"role_id": ctx.data.role_id,
			"role_name": ctx.data.role_name,
			"permission": ctx.data.permission,
			"comment": ctx.data.comment,
			"create_date": Math.floor(new Date().getTime() / 1000),
			// "status": 0,
		})
		ctx.data.nickname
		if (res.errCode) {
			ctx.body = {
				"code": 0,
				"msg": res.errMsg,
				"data": {}
			}
		}

		ctx.body = {
			"code": 200,
			"msg": "添加成功",
			"data": {}
		}
	}

	async edit(event, context) {
		const {
			ctx,
			service
		} = this;
		ctx.headers = {
			'content-type': 'application/json'
		}

		// 获取记录
		let res = await db.collection(tableName).doc(ctx.data.id).get();
		let info = res.data[0];
		
		// 菜单构造权限
		function addAdminAuthToMenu(menus) {  
		    // 遍历数组中的每个菜单项  
		    menus.forEach(val => {
				let apiPrefix = ''
				if (val['apiPrefix']) {
					apiPrefix = '/' + val['apiPrefix']
				}
		        val['adminAuth'] = apiPrefix + '/admin' + val['path'];
		  
		        // 如果当前菜单项有子菜单（children），则递归处理  
		        if (val.children && val.children.length > 0) {  
		            val.children = addAdminAuthToMenu(val.children);  
		        }  
		    });
			return menus;
		}  
		let dataList = JSON.parse(JSON.stringify(ctx.config.menus));
		let menuTree = addAdminAuthToMenu(dataList);

		let xyBuilderForm = new XyBuilderForm();
		xyBuilderForm
			.init()
			.addFormItem("id", "ID", "hidden", ctx.data.id, {})
			.addFormItem("role_id", "角色标识", "text", "", {})
			.addFormItem("role_name", "角色名称", "text", "", {})
			.addFormItem("comment", "备注", "text", "", {})
			.addFormItem("permission", "后台权限", "checkboxtree", [], {
				'tip': '勾选角色权限',
				'columns': [
				    {'title': '菜单(接口)', 'name': 'title', 'minWidth': '150px'},
				    {'title': '接口', 'name': 'adminAuth'},
				    {'title': '类型', 'name': 'menuType', 'width': '40px'},
				    {'title': '说明', 'name': 'tip'},
				],
				'data': menuTree,
				'nodeKey': 'adminAuth',
				'position': 'bottom'
			})
			.setFormValues(info)
			.setConfig('submitApi', '/core/admin/role/doEdit')
		ctx.body = {
			"code": 200,
			"msg": "成功",
			"data": {
				res: res,
				formData: xyBuilderForm.getData()
			}
		}
	}

	async doEdit(event, context) {
		const {
			ctx,
			service
		} = this;
		ctx.headers = {
			'content-type': 'application/json'
		}
		
		// 查重复
		let exist = await db.collection(tableName).where({
			role_id: ctx.data.role_id,
			id: dbCmd.not(dbCmd.eq(ctx.data.id)),
		}).get()
		if (exist.data.length && exist.data.length > 0) {
			if (ctx.data.id != exist.data[0]._id) {
				ctx.body = {
					"code": 0,
					"msg": '角色标识已存在',
					"data": {}
				}
			} else {
				if (exist.data[0].role_id == 'admin') {
					ctx.body = {
						"code": 0,
						"msg": '超级管理员角色不允许修改',
						"data": {}
					}
				}
			}
		}

		// 数据
		let data = {
			"role_id": ctx.data.role_id,
			"role_name": ctx.data.role_name,
			"permission": ctx.data.permission,
			"comment": ctx.data.comment,
		}
		let res = await db.collection(tableName)
			.doc(ctx.data.id).update(data)
		if (res.errCode) {
			ctx.body = {
				"code": 0,
				"msg": res.errMsg,
				"data": {}
			}
		}

		ctx.body = {
			"code": 200,
			"msg": "修改成功",
			"data": {}
		}
	}
};