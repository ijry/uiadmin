// middleware/log.js
module.exports = (extName) => {
  // 返回中间件函数
  return async function log(ctx, next) {
	let fullPath = '/' + extName + ctx.event.path;
	try{
		let res = await ctx.db.collection('opendb-admin-log').add({
			"user_id": ctx?.auth?.uid || '',
			"nickname": '',
			"content": '访问' + fullPath,
			"ip": ctx.context.CLIENTIP,
			"register_date": Math.floor(new Date().getTime() / 1000)
		})
	} catch(e) {
		// ctx.body = {
		//   code: 1,
		//   msg: '日志出错' + JSON.stringify(e),
		//   // data: {}
		//   data: JSON.parse(JSON.stringify(ctx))
		// }
		// return;
	}
	await next(); // 执行后续中间件
  };
};
