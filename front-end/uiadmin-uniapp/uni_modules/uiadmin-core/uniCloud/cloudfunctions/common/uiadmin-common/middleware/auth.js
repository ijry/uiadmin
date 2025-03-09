// middleware/auth.js
const uniID = require('uni-id-common')
module.exports = (extName) => {
  // 返回中间件函数
  return async function auth(ctx, next) {
	// ctx.body = {
	//   code: 1,
	//   msg: '测试' + extName,
	//   // data: {}
	//   data: JSON.parse(JSON.stringify(ctx))
	// }
	// return;
	  
    // 校验 token
	const uniIDIns = uniID.createInstance({ // 创建uni-id实例
	      context: ctx,
	    // config: {} // 完整uni-id配置信息，使用config.json进行配置时无需传此参数
	})
	// ctx.event.uniIdToken
	let fullPath = '/' + extName + ctx.event.path;
	if (fullPath == '/core/index/xyadmin'
		|| fullPath == '/core/index/xyadminApi'
		|| fullPath == '/core/admin/user/login') {
		await next(); // 执行后续中间件
		return;
	}
	if (!ctx.event.headers?.authorization) {
		ctx.body = {
		  code: 401,
		  msg: '请登录后台',
		  data: {}
		}
		return;
	} else {
		const auth = await uniIDIns.checkToken(ctx.event.headers?.authorization) // 后续使用uniIDIns调用相关接口
		if (auth.code) {
		  // 校验失败，抛出错误信息
		  ctx.body = {
			  code: 401,
			  msg: auth.code + auth.message,
			  data: {}
		  }
		  return
		}
		
		// 用户角色权限
		// 获取模块名称如uiadmin-core中的core
		let sharedMenus = ctx.config?.sharedMenus || [];
		if (!sharedMenus.includes(fullPath)
			&& !auth.permission.includes('ROLE_SUPER_ADMIN')
			&& !auth.permission.includes(fullPath)) {
			// 校验失败，抛出错误信息
			ctx.body = {
			  code: 402,
			  msg: '权限不足' + ctx.event.path,
			  data: {}
			}
			return;
		}
		
		ctx.auth = auth; // 设置当前请求的 auth 对象
		ctx.dbJql = uniCloud.databaseForJQL();
		ctx.dbJql.setUser(ctx.auth);
		await next(); // 执行后续中间件
	}
  };
};
