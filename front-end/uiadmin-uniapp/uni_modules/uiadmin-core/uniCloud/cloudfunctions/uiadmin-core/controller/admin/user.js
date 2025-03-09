const {
	Controller
} = require("uni-cloud-router");
const {
	XyBuilderList,
	XyBuilderForm,
	encryptPwd
} = require("uiadmin-common");
const uniID = require('uni-id-common')
const db = uniCloud.database(); //代码块为cdb
const tableName = 'uni-id-users';

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
			.addColumn("nickname", "昵称", {
				type: "text"
			})
			.addColumn("avatar", "头像", {
				type: "image",
			})
			.addColumn("role", "角色", {
				type: "tags",
				options: []
			})
			.addColumn("mobile", "手机号", {
				type: "text"
			})
			.addColumn("email", "邮箱", {
				type: "text"
			})
			.addColumn("status", "状态", {
				prefixType: "dot",
				useOptions: true,
				options: [{
						title: "正常",
						value: 0,
						color: "green"
					},
					{
						title: "禁用",
						value: 1,
						color: "red"
					},
					{
						title: "审核中",
						value: 2,
						color: "#ff9d28"
					},
					{
						title: "审核拒绝",
						value: 3,
						color: "#ff9d28"
					}
				]
			})
			.addColumn("register_date", "创建时间", {
				type: "time",
			})
			.addTopButton("add", "添加", {
				title: "新增",
				pageType: "modal",
				modalType: "form",
				api: "/core/admin/user/add",
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
				api: "/core/admin/user/edit",
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

		let xyBuilderForm = new XyBuilderForm();
		xyBuilderForm
			.init()
			.addFormItem("nickname", "昵称", "text", "", {})
			.addFormItem("username", "用户名", "text", "", {})
			.addFormItem("password", "密码", "text", "", {})
			.addFormItem("avatar", "头像", "image", "", {})
			.addFormItem("mobile", "手机号", "text", "", {})
			.addFormItem("mobile_confirmed", "手机号验证", "radio", "", {
				options: [{
						title: "未验证",
						value: 0,
						color: "red"
					},
					{
						title: "已验证",
						value: 1,
						color: "green"
					},
				]
			})
			.addFormItem("email", "邮箱", "text", "", {})
			.addFormItem("email_confirmed", "邮箱验证", "radio", "", {
				options: [{
						title: "未验证",
						value: 0,
						color: "red"
					},
					{
						title: "已验证",
						value: 1,
						color: "green"
					},
				]
			})
			.addFormItem("role", "用户角色", "checkbox", [], {
				options: [{
					title: "管理员",
					value: 'admin'
				}, ]
			})
			.addFormItem("status", "状态", "select", "", {
				options: [{
						title: "正常",
						value: 0,
						color: "green"
					},
					{
						title: "禁用",
						value: 1,
						color: "red"
					},
					{
						title: "审核中",
						value: 2,
						color: "#ff9d28"
					},
					{
						title: "审核拒绝",
						value: 3,
						color: "#ff9d28"
					}
				]
			})
			.setConfig('submitApi', '/core/admin/user/doAdd')
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

		const {
			passwordHash,
			version
		} = encryptPwd(ctx.data.password)

		// 单条插入数据
		let res = await db.collection(tableName).add({
			"username": ctx.data.username,
			"nickname": ctx.data.nickname,
			"password": passwordHash,
			"avatar": ctx.data.avatar,
			"mobile": ctx.data.mobile,
			"mobile_confirmed": ctx.data.mobile_confirmed,
			"role": ctx.data.role,
			"comment": "",
			"register_date": Math.floor(new Date().getTime() / 1000),
			"status": 0,
			"password_secret_version": 1
		})
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

		// const db = uniCloud.database(); //代码块为cdb
		let res = await db.collection(tableName)
			.doc(ctx.data.id).get();
		res.data[0]['password'] = '';
		let info = res.data[0];

		let xyBuilderForm = new XyBuilderForm();
		xyBuilderForm
			.init()
			.addFormItem("id", "ID", "hidden", ctx.data.id, {})
			.addFormItem("nickname", "昵称", "text", "", {})
			.addFormItem("username", "用户名", "text", "", {})
			.addFormItem("password", "密码", "text", "", {})
			.addFormItem("avatar", "头像", "image", "", {})
			.addFormItem("mobile", "手机号", "text", "", {})
			.addFormItem("mobile_confirmed", "手机号验证", "radio", "", {
				options: [{
						title: "未验证",
						value: 0,
						color: "red"
					},
					{
						title: "已验证",
						value: 1,
						color: "green"
					},
				]
			})
			.addFormItem("email", "邮箱", "text", "", {})
			.addFormItem("email_confirmed", "邮箱验证", "radio", "", {
				options: [{
						title: "未验证",
						value: 0,
						color: "red"
					},
					{
						title: "已验证",
						value: 1,
						color: "green"
					},
				]
			})
			.addFormItem("role", "用户角色", "checkbox", [], {
				options: [{
					title: "管理员",
					value: 'admin'
				}, ]
			})
			.addFormItem("status", "状态", "select", "", {
				options: [{
						title: "正常",
						value: 0,
						color: "green"
					},
					{
						title: "禁用",
						value: 1,
						color: "red"
					},
					{
						title: "审核中",
						value: 2,
						color: "#ff9d28"
					},
					{
						title: "审核拒绝",
						value: 3,
						color: "#ff9d28"
					}
				]
			})
			.setFormValues(info)
			.setConfig('submitApi', '/core/admin/user/doEdit')
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

		// 数据
		let data = {
			"username": ctx.data.username,
			"nickname": ctx.data.nickname,
			"avatar": ctx.data.avatar,
			"mobile": ctx.data.mobile,
			"mobile_confirmed": ctx.data.mobile_confirmed,
			"role": ctx.data.role,
			"status": 0,
		}
		if (ctx.data?.password && ctx.data.password != '') {
			const {
				passwordHash,
				version
			} = encryptPwd(ctx.data.password)
			data.password = passwordHash
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

	async login() {
		const {
			ctx,
			service
		} = this;
		ctx.headers = {
			'content-type': 'application/json'
		}

		// const db = uniCloud.database(); //代码块为cdb
		try {
			let res = await db.collection(tableName)
				.where({
					username: ctx.data.account
				})
				.get()
			if (res.data.length == 0) {
				ctx.body = {
					"code": 200,
					"msg": "用户不存在",
					"data": {}
				}
				return
			}
			let userInfo = res.data[0]
			// 密码判断
			const {
				passwordHash,
				version
			} = encryptPwd(ctx.data.password)
			if (userInfo.password != passwordHash) {
				ctx.body = {
					"code": 0,
					"msg": "密码错误",
					"data": {}
				}
				return
			}

			// 角色权限
			let permits = userInfo.role.map((ele) => {
				if (ele == 'admin') {
					ele = 'super_admin'
				}
				return 'ROLE_' + ele.toUpperCase()
			})
			const uniIDIns = uniID.createInstance({ // 创建uni-id实例
				context: ctx,
				// config: {} // 完整uni-id配置信息，使用config.json进行配置时无需传此参数
			})
			let tokenRes = await uniIDIns.createToken({
				uid: userInfo._id,
				role: userInfo.role,
				permission: permits
			})

			ctx.body = {
				"code": 200,
				"msg": "登录成功",
				"tokenRes": tokenRes,
				"data": {
					"token": tokenRes.token,
					"tokenExpired": tokenRes.tokenExpired,
					"userInfo": {
						"id": userInfo._id,
						"nickname": userInfo.nickname,
						"username": userInfo.username,
						"country": "+86",
						"mobile": userInfo.mobile,
						"email": userInfo.email,
						"avatar": userInfo.avatar,
						"roles": userInfo.role,
						"authorities": permits,
						"status": 1,
					}
				}
			}
		} catch (e) {
			ctx.body = {
				"code": 0,
				"msg": "出错" + e.message,
				"data": ctx.data
			}
			return
		}
	}

	async info() {
		const {
			ctx,
			service
		} = this;
		ctx.headers = {
			'content-type': 'application/json'
		}
		// 获取登录用户
		let res = await db.collection(tableName)
			.doc(ctx.auth.uid).get()
		let userInfo = res.data[0]
		// 角色权限
		let permits = userInfo.role.map((ele) => {
			if (ele == 'admin') {
				ele = 'super_admin'
			}
			return 'ROLE_' + ele.toUpperCase()
		})
		ctx.body = {
			"code": 200,
			"msg": "success",
			"data": {
				"userInfo": {
					"id": userInfo.id,
					"nickname": userInfo.nickname,
					"username": userInfo.username,
					"country": "+86",
					"mobile": userInfo.mobile,
					"email": userInfo.email,
					"avatar": userInfo.avatar,
					"roles": userInfo.role,
					"authorities": permits,
					"status": userInfo.status,
				}
			}
		}
	}

	async logout() {
		const {
			ctx,
			service
		} = this;
		ctx.headers = {
			'content-type': 'application/json'
		}
		ctx.body = {
			"code": 200,
			"msg": "success",
			"data": {}
		}
	}
};