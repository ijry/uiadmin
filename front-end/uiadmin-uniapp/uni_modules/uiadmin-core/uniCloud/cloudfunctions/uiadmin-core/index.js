// index.js (通常无需改动)
const uniID = require('uni-id-common');
const Router = require("uni-cloud-router").Router; // 引入 Router
const router = new Router(require("./config.js")); // 根据 config 初始化 Router

exports.main = async (event, context) => {
  // console.log(event, context)
  return router.serve(event, context); // 由 Router 接管云函数
};
