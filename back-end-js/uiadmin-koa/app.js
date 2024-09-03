// 必须，支持node_modules中的装饰器等语法
require("@babel/register")({
  // This will override `node_modules` ignoring - you can alternatively pass
  // an array of strings to be explicitly matched or a regex / glob
  ignore: [],
  plugins: [
    ["@babel/plugin-proposal-decorators", { "legacy": true }],
    ["@babel/plugin-proposal-class-properties", { "loose": true }],
    ["@babel/plugin-proposal-private-methods", { "loose": true }],
    ["@babel/plugin-proposal-private-property-in-object", { "loose": true }]
  ]
});
const Koa = require('koa');
const Router = require('@koa/router');
const app = new Koa();
var router = new Router();
const port = 4000

// 配置
const myconfig = require('config-lite')({
  filename: 'default',
  config_basedir: __dirname,
  config_dir: 'config'
});

// 加载接口控制器需要在uiadmin调用之前
// 注意这里需要引入所有的控制器路由装饰器才会生效
require('./appext/demo-blog/controller/DemoController')


// 自动引入uiadmin
var fs = require("fs");
var checkDir = fs.existsSync("./appext/uiadmin-core");
let uiadminAlias = './appext/uiadmin-core';
if (!checkDir) {
  uiadinAlias = 'uiadmin-koa'
}
// 调用
const { uiadmin, config } =  require(uiadinAlias)
uiadmin(app, router, myconfig)

// 首页
router.get('/', (ctx, next) => {
  ctx.body = "<div style='text-align:center'><a href='/xyadmin/'>点击打开UiAdmin通用后台</a>，账号admin密码uiadmin。</div><iframe style='width: 100%;height: calc(100vh - 20px)' src='/xyadmin/'></iframe>"
});

app.listen(port, () => {
  console.log(`http://127.0.0.1:${port}`)
})

