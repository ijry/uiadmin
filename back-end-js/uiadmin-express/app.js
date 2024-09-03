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

const express = require('express')
const app = express()
const port = 4000
var bodyParser = require('body-parser');
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));

// 配置
const myconfig = require('config-lite')({
  filename: 'default',
  config_basedir: __dirname,
  config_dir: 'config'
});

// 自动引入
var fs = require("fs");
var checkDir = fs.existsSync("./appext/uiadmin-core");
let uiadminAlias = './appext/uiadmin-core';
if (!checkDir) {
  uiadminAlias = 'uiadmin-express'
}
const {
  Controller, Get, RootUrl, Post, MenuItem, UiAdmin, config, XyBuilderList, XyBuilderForm
} = require(uiadminAlias)

// 调用uiadmin
app.use(new UiAdmin())
config.configs = myconfig


// 默认控制器
@Controller
class IndexController {
  // 首页
  @Get('/')
  home(req, res) {
    res.send("<div style='text-align:center'><a href='/xyadmin/'>点击打开UiAdmin通用后台</a>，账号admin密码uiadmin。</div><iframe style='width: 100%;height: calc(100vh - 20px)' src='/xyadmin/'></iframe>")
  }
}
app.use(new IndexController())

const DemoController = require('./appext/demo-blog/controller/DemoController.js')
app.use(new DemoController())


app.listen(port, () => {
  console.log(`http://127.0.0.1:${port}`)
})

