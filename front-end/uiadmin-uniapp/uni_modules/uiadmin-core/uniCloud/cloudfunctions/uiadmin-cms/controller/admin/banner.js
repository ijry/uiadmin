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
const tableName = 'opendb-banner';

module.exports = class BannerController extends Controller {
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
			.addColumn("category_id", "分类", {
				type: "text",
			})
			.addColumn("bannerfile.url", "图片", {
				type: "image"
			})
			.addColumn("open_url", "目标地址", {
				type: "text",
			})
			.addColumn("title", "标题", {
				type: "text"
			})
			.addColumn("sort", "排序", {
				type: "text"
			})
			.addColumn("description", "备注", {
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
				]
			})
			.addColumn("create_date", "创建时间", {
				type: "time",
			})
			.addTopButton("add", "添加", {
				title: "新增",
				pageType: "modal",
				modalType: "form",
				api: "/cms/admin/banner/add",
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
				api: "/cms/admin/banner/edit",
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
			.addFormItem("category_id", "分类ID", "text", "", {})
			.addFormItem("banner_url", "Banner图片", "image", "", {
			})
			.addFormItem("open_url", "目标地址", "text", "", {})
			.addFormItem("title", "标题", "text", "", {})
			.addFormItem("sort", "排序", "text", "", {})
			.addFormItem("description", "备注", "text", "", {})
			.setConfig('submitApi', '/cms/admin/banner/doAdd')
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
			"category_id": ctx.data.category_id,
			"bannerfile": {
				url: ctx.data.banner_url
			},
			"open_url": ctx.data.open_url,
			"title": ctx.data.title,
			"sort": ctx.data.sort,
			"description": ctx.data.description,
			// "create_date": Math.floor(new Date().getTime() / 1000),
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
			.addFormItem("category_id", "分类ID", "text", "", {})
			.addFormItem("banner_url", "Banner图片", "image", "", {
			})
			.addFormItem("open_url", "目标地址", "text", "", {})
			.addFormItem("title", "标题", "text", "", {})
			.addFormItem("sort", "排序", "text", "", {})
			.addFormItem("description", "备注", "text", "", {})
			.setFormValues(info)
			.setConfig('submitApi', '/cms/admin/banner/doEdit')
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
			"category_id": ctx.data.category_id,
			"bannerfile": {
				url: ctx.data.banner_url
			},
			"open_url": ctx.data.open_url,
			"title": ctx.data.title,
			"sort": ctx.data.sort,
			"description": ctx.data.description,
			// "create_date": Math.floor(new Date().getTime() / 1000),
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
