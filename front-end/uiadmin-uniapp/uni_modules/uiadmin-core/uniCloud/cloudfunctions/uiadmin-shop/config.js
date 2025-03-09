// config.js
const { midAuth, midLog } = require('uiadmin-common') // 引入 auth 中间件

const extName = 'shop';
module.exports = {
  debug: true, // 调试模式时，将返回 stack 错误堆栈
  baseDir: __dirname, // 必选，应用根目录
  multiApp: false, // 开启多应用
  middleware: [
      [
        // 数组格式，第一个元素为中间件，第二个元素为中间件生效规则配置
        midAuth(extName), // 注册中间件
        { enable: true, match: /.*admin\/.*/ },
      ],
	  [
	    // 数组格式，第一个元素为中间件，第二个元素为中间件生效规则配置
	    midLog(extName), // 注册中间件
		{ enable: true, match: /.*admin\/.*/ },
	  ],
  ],
  sharedMenus: [
  ],
};
