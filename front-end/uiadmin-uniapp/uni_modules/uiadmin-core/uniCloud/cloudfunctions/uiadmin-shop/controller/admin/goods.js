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
const tableName = 'opendb-mall-goods';

module.exports = class GoodsController extends Controller {
	// 商品列表
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
			.addColumn("category_id", "分类ID", {
				type: "text",
			})
			.addColumn("goods_sn", "货号", {
				type: "text"
			})
			.addColumn("goods_thumb", "缩略图", {
				type: "image",
			})
			.addColumn("goods_banner_imgs", "banner图", {
				type: "images",
			})
			.addColumn("name", "商品名称", {
				type: "text",
			})
			.addColumn("remain_count", "库存数量", {
				type: "text"
			})
			.addColumn("month_sell_count", "月销量", {
				type: "text"
			})
			.addColumn("add_date", "上架时间", {
				type: "time",
			})
			.addTopButton("add", "添加", {
				title: "新增",
				pageType: "modal",
				modalType: "form",
				api: "/shop/admin/goods/add",
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
				api: "/shop/admin/goods/edit",
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

	// 添加商品
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
			.addFormItem("goods_sn", "货号", "text", "", {
			})
			.addFormItem("goods_thumb", "缩略图", "image", "", {
			})
			.addFormItem("goods_banner_imgs", "banner图", "images", "", {
			})
			.addFormItem("name", "商品名称", "text", "", {})
			.addFormItem("remain_count", "库存", "text", "", {})
			.addFormItem("month_sell_count", "月销量", "text", "", {})
			.setConfig('submitApi', '/shop/admin/goods/doAdd')
		ctx.body = {
			"code": 200,
			"msg": "成功",
			"data": {
				formData: xyBuilderForm.getData()
			}
		}
	}

	// 保存商品
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
			"category_id": ctx.data.category_id,
			"goods_sn": ctx.data.goods_sn,
			"goods_thumb": ctx.data.goods_thumb,
			"goods_banner_imgs": ctx.data.goods_banner_imgs,
			"remain_count": ctx.data.remain_count,
			"month_sell_count": ctx.data.month_sell_count,
			"add_date": Math.floor(new Date().getTime() / 1000),
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
			.addFormItem("category_id", "分类ID", "text", "", {})
			.addFormItem("goods_sn", "货号", "text", "", {
			})
			.addFormItem("goods_thumb", "缩略图", "image", "", {
			})
			.addFormItem("goods_banner_imgs", "banner图", "images", "", {
			})
			.addFormItem("name", "商品名称", "text", "", {})
			.addFormItem("remain_count", "库存", "text", "", {})
			.addFormItem("month_sell_count", "月销量", "text", "", {})
			.setFormValues(info)
			.setConfig('submitApi', '/shop/admin/goods/doEdit')
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
			"category_id": ctx.data.category_id,
			"goods_sn": ctx.data.goods_sn,
			"goods_thumb": ctx.data.goods_thumb,
			"goods_banner_imgs": ctx.data.goods_banner_imgs,
			"remain_count": ctx.data.remain_count,
			"month_sell_count": ctx.data.month_sell_count,
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
