const { Controller } = require("uni-cloud-router");
module.exports = class SiteController extends Controller {
  async info() {
  	const { ctx, service } = this;
  	ctx.headers = {'content-type': 'application/json'}
  	ctx.body = {
  		"code": 200,
  		"msg": "success",
  		"data": {
  		}
  	}
  }
};
