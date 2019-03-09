const path = require('path')
const resolve = dir => {
  return path.join(__dirname, dir)
}

module.exports = {
  publicPath: process.env.NODE_ENV === 'production' ? './' : './', // 路径
  lintOnSave: true,
  chainWebpack: config => {
    config.resolve.alias
      .set('@', resolve('src')) // key,value自行定义，比如.set('@@', resolve('src/components'))
      .set('_c', resolve('src/components'))
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
