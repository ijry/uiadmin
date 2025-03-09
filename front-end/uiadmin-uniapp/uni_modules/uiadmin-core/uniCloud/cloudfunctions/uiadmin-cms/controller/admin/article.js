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
const tableName = 'opendb-news-articles';

module.exports = class ArticleController extends Controller {
	constructor(ctx) {
		super(ctx)
	}
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
			.addColumn("user_id", "作者ID", {
				type: "text",
			})
			.addColumn("category_id", "文章分类", {
				type: "text",
			})
			.addColumn("avatar", "封面图", {
				type: "image",
			})
			.addColumn("title", "文章标题", {
				type: "text",
				width: '270px'
			})
			.addColumn("article_status", "状态", {
				type: "tag",
				useOptions: true,
				options: [{
						title: "草稿箱",
						value: 0,
						color: "red"
					},
					{
						title: "已发布",
						value: 1,
						color: "green"
					},
				]
			})
			.addColumn("is_sticky", "置顶", {
				type: "tag",
				prefixType: "dot",
				useOptions: true,
				options: [{
						title: "未置顶",
						value: false,
						color: "red"
					},
					{
						title: "已置顶",
						value: true,
						color: "green"
					},
				]
			})
			.addColumn("publish_date", "发布时间", {
				type: "time",
				width:'170px'
			})
			.addColumn("is_essence", "精华", {
				type: "tag",
				prefixType: "dot",
				useOptions: true,
				options: [{
						title: "非精华",
						value: false,
						color: "red"
					},
					{
						title: "精华",
						value: true,
						color: "green"
					},
				]
			})
			.addColumn("view_count", "阅读数量", {
				type: "text",
			})
			.addColumn("like_count", "点赞数", {
				type: "text",
			})
			.addColumn("comment_count", "评论数", {
				type: "text",
			})
			.addTopButton("add", "添加", {
				title: "新增",
				pageType: "modal",
				modalType: "form",
				api: "/cms/admin/article/add",
				width: "1100px"
			})
			.addColumn("rightButtonList", "操作", {
				type: "rightButtonList",
				minWidth: "120px"
			})
			.addRightButton("edit", "修改", {
				title: "修改",
				pageType: "modal",
				modalType: "form",
				api: "/cms/admin/article/edit",
				apiSuffix: [],
				querySuffix: [
					['id', '_id']
				],
				width: "1100px",
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
		
		// 分类列表
		let cateRes = await ctx.dbJql.collection('opendb-news-categories')
			.field('name as title, _id as value')
			.orderBy("sort", "asc").get()

		let xyBuilderForm = new XyBuilderForm();
		xyBuilderForm
			.init()
			.addFormItem("category_id", "分类ID", "select", "", {
				options: cateRes.data
			})
			.addFormItem("avatar", "Banner图片", "image", "", {
			})
			.addFormItem("title", "文章标题", "text", "", {})
			.addFormItem("excerpt", "文章摘要", "textarea", "", {})
			.addFormItem("content", "文章内容", "html", "", {})
			.addFormItem("article_status", "状态", "radio", "", {
				'options': [{
						title: "草稿箱",
						value: 0,
						color: "green"
					},
					{
						title: "已发布",
						value: 1,
						color: "red"
					},
				]
			})
			.addFormItem("is_sticky", "置顶", "radio", false, {
				'options': [{
						title: "未置顶",
						value: false,
						color: "red"
					},
					{
						title: "已置顶",
						value: true,
						color: "green"
					},
				]
			})
			.addFormItem("comment_status", "开放评论", "radio", "", {
				'options': [{
						title: "关闭",
						value: 0,
						color: "red"
					},
					{
						title: "开启",
						value: 1,
						color: "green"
					},
				]
			})
			.setConfig('submitApi', '/cms/admin/article/doAdd')
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
		let res = await ctx.dbJql.collection(tableName).add({
			"user_id": ctx.auth.uid,
			"category_id": ctx.data.category_id,
			"avatar": ctx.data.avatar,
			"title": ctx.data.title,
			"excerpt": ctx.data.excerpt,
			"content": ctx.data.content,
			"article_status": ctx.data.article_status,
			"is_sticky": ctx.data.is_sticky ? true: false,
			"comment_status": ctx.data.comment_status,
			"publish_date": Math.floor(new Date().getTime() / 1000),
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
		
		// 分类列表
		let cateRes = await ctx.dbJql.collection('opendb-news-categories')
			.field('name as title, _id as value')
			.orderBy("sort", "asc").get()

		let xyBuilderForm = new XyBuilderForm();
		xyBuilderForm
			.init()
			.addFormItem("id", "ID", "hidden", ctx.data.id, {})
			.addFormItem("category_id", "分类ID", "select", "", {
				options: cateRes.data
			})
			.addFormItem("avatar", "Banner图片", "image", "", {
			})
			.addFormItem("title", "文章标题", "text", "", {})
			.addFormItem("excerpt", "文章摘要", "textarea", "", {})
			.addFormItem("content", "文章内容", "html", "", {})
			.addFormItem("article_status", "状态", "radio", "", {
				'options': [{
						title: "草稿箱",
						value: 0,
						color: "green"
					},
					{
						title: "已发布",
						value: 1,
						color: "red"
					},
				]
			})
			.addFormItem("is_sticky", "置顶", "radio", "", {
				'options': [{
						title: "未置顶",
						value: false,
						color: "red"
					},
					{
						title: "已置顶",
						value: true,
						color: "green"
					},
				]
			})
			.addFormItem("comment_status", "开放评论", "radio", "", {
				'options': [{
						title: "关闭",
						value: 0,
						color: "red"
					},
					{
						title: "开启",
						value: 1,
						color: "green"
					},
				]
			})
			.setFormValues(info)
			.setConfig('submitApi', '/cms/admin/article/doEdit')
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
			"avatar": ctx.data.avatar,
			"title": ctx.data.title,
			"excerpt": ctx.data.excerpt,
			"content": ctx.data.content,
			"article_status": ctx.data.article_status,
			"is_sticky": ctx.data.is_sticky ? true: false,
			"comment_status": ctx.data.comment_status,
			"publish_date": Math.floor(new Date().getTime() / 1000),
		}
		let res = await ctx.dbJql.collection(tableName)
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
