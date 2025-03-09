const { Controller } = require("uni-cloud-router");
module.exports = class MenuController extends Controller {
  async trees() {
  	const { ctx, service } = this;
  	ctx.headers = {'content-type': 'application/json'}
	// let menuTree = list2tree(menuList, null)
	let menuTree = []
  	ctx.body = {
  		"code": 200,
  		"msg": "success",
  		"data": {
			"menu2routes": true,
			"listData": {
				"dataList": ctx.config.menus
			}
  		}
  	}
  }
};
