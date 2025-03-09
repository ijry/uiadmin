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
const tableName = 'opendb-admin-log';

module.exports = class LogController extends Controller {
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
			.addColumn("user_id", "用户ID", {
				type: "text"
			})
			// .addColumn("user_name", "用户昵称", {
			// 	type: "text",
			// })
			.addColumn("content", "日志内容", {
				type: "text",
				options: []
			})
			.addColumn("ip", "IP", {
				type: "text"
			})
			.addColumn("register_date", "创建时间", {
				type: "time",
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
};