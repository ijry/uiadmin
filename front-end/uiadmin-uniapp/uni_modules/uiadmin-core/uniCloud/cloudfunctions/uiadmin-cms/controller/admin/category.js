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
const tableName = 'opendb-news-categories';

module.exports = class CategoryController extends Controller {
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
			.addColumn("name", "分类名称", {
				type: "text",
			})
			.addColumn("icon", "图标", {
				type: "image"
			})
			.addColumn("article_count", "文章数量", {
				type: "text",
			})
			.addColumn("sort", "排序", {
				type: "text"
			})
			.addColumn("create_date", "创建时间", {
				type: "time",
			})
			.addTopButton("add", "添加", {
				title: "新增",
				pageType: "modal",
				modalType: "form",
				api: "/cms/admin/category/add",
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
				api: "/cms/admin/category/edit",
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
			.addFormItem("name", "分类名称", "text", "", {})
			.addFormItem("icon", "分类图标", "image", "", {
			})
			.addFormItem("description", "描述", "textarea", "", {})
			.addFormItem("sort", "排序", "text", "", {})
			.setConfig('submitApi', '/cms/admin/category/doAdd')
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

		// 单条插入数据
		let res = await db.collection(tableName).add({
			"name": ctx.data.name,
			"icon": ctx.data.icon,
			"sort": ctx.data.sort,
			"description": ctx.data.description,
			"create_date": Math.floor(new Date().getTime() / 1000),
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

		// 获取记录
		let res = await db.collection(tableName).doc(ctx.data.id).get();
		let info = res.data[0];

		let xyBuilderForm = new XyBuilderForm();
		xyBuilderForm
			.init()
			.addFormItem("id", "ID", "hidden", ctx.data.id, {})
			.addFormItem("name", "分类名称", "text", "", {})
			.addFormItem("icon", "分类图标", "image", "", {
			})
			.addFormItem("description", "描述", "textarea", "", {})
			.addFormItem("sort", "排序", "text", "", {})
			.setFormValues(info)
			.setConfig('submitApi', '/cms/admin/category/doEdit')
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
			"name": ctx.data.name,
			"icon": ctx.data.icon,
			"sort": ctx.data.sort,
			"description": ctx.data.description,
			"create_date": Math.floor(new Date().getTime() / 1000),
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
