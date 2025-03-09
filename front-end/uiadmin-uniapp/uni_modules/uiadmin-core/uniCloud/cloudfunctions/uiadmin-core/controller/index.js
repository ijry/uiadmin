const { Controller } = require("uni-cloud-router");
const https = require('https')
function getAdmin() {
  return new Promise((resolve) => {
    https.get('https://uiadmin.net/xyadmin/?version=2.2.0', ret => {
      let list = [];
      ret.on('data', chunk => {
          list.push(chunk);
      });
      ret.on('end', () => {
        let html = Buffer.concat(list).toString()
        resolve(html)
      });
    }).on('error', err => {
      console.log('Error: ', err.message)
    })
  })
}
module.exports = class IndexController extends Controller {
  async xyadmin() {
	const { ctx, service } = this;
	ctx.headers = {'content-type':'text/html'}
	let html = await getAdmin()
	ctx.body = html;
  }
  
  async xyadminApi(event) {
  	const { ctx, service } = this;
  	ctx.headers = {'content-type': 'application/json'}
  	let host = "https://" + event.event.headers.host
  	ctx.body = {
		"event": JSON.parse(JSON.stringify(event)),
  		"code": 200,
  		"msg": "success",
  		"data": {
  			"framework": "uniCloud",
  			"stype": "应用",
  			"name": "uiadmin-unicloud",
  			"api": {
  				"apiLogin": "/core/admin/user/login",
				"apiPrefix": "/uiadmin-api",
  				"apiConfig": "/core/site/info",
  				"apiBase": host + "/uiadmin-api",
  				"apiUserInfo": "/core/admin/user/info",
  				"apiAdmin": "/core/admin/index/index",
  				"apiMenuTrees": "/core/admin/menu/trees"
  			},
  			"lang": "js",
  			"title": 'UiAdmin',
  			"domainRoot": host,
  			"siteInfo": {
  				"title": 'UiAdmin'
  			},
  			"version": "1.0.0",
  			"config": {
  				"useVerify": "",
  				// "headerRightToolbar": [
  				//         {
  				//             "type": "url",
  				//             "title": "接口文档",
  				//             "class": "xyicon xyicon-map",
  				//             "url": "/doc.html"
  				//         }
  				// ]
  			}
  		}
  	}
  }
};
