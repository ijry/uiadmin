/**
 * +----------------------------------------------------------------------
 * | InitAdmin/vue-admin [ InitAdmin渐进式模块化通用后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2019 http://initadmin.net All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <ijry@qq.com>
 * +----------------------------------------------------------------------
*/

const path = require('path')
const resolve = dir => {
  return path.join(__dirname, dir)
}

module.exports = {
  publicPath: process.env.NODE_ENV === 'production' ? './' : './', // 路径
  outputDir: 'dist', // 输出文件目录
  lintOnSave: false,
  chainWebpack: config => {
    config.resolve.alias
      .set('@', resolve('src')) // key,value自行定义，比如.set('@@', resolve('src/components'))
      .set('@c', resolve('src/components'))
  },
  devServer: {
      open: true,
      host: '0.0.0.0',
      port: 8081,
      https: false,
      hotOnly: true,
      proxy: null,
      disableHostCheck: true,
      // proxy: {
      //     '/api': {
      //         target: '<url>',
      //         ws: true,
      //         changOrigin: true
      //     }
      // },
      before: app => {}
  },
  productionSourceMap: false, // 设为false打包时不生成.map文件
  pluginOptions: {
    i18n: {
      locale: 'zh',
      fallbackLocale: 'zh',
      localeDir: 'locales',
      enableInSFC: false
    }
  },
  css: {
    loaderOptions: {
        less: {
            javascriptEnabled: true,
        }
    }
  }
}
