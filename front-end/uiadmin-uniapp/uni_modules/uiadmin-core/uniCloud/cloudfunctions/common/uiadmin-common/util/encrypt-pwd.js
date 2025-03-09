const crypto = require('crypto')
const createConfig = require('uni-config-center')
const shareConfig = createConfig({ // 获取配置实例
    pluginId: 'uni-id' // common/uni-config-center下的插件配置目录名
})
const config = shareConfig.config() // 获取common/uni-config-center/uni-id/config.json的内容
// import {
//   getType
// } from '../../share/index'

function encryptPwd (password, { value: secret, version } = {}) {
  password = password && password.trim()
  if (!password) {
	throw new Error('密码不能为空')
    // throw new Error(this.t('param-required', {
    //   param: this.t('password')
    // }))
  }
  if (!secret) {
    const {
      passwordSecret
    } = config
    // const passwordSecretType = getType(passwordSecret)
    // if (passwordSecretType === 'array') {
      const secretList = passwordSecret.sort((a, b) => {
        return a.version - b.version
      })
	  let type = secretList[secretList.length - 1].type
      secret = secretList[secretList.length - 1].value
      version = secretList[secretList.length - 1].version
    // } else {
    //   secret = passwordSecret
    // }
  }
  if (!secret) {
	throw new Error('passwordSecret不能为空' + JSON.stringify(config))
    // throw new Error(this.t('param-error', {
    //   param: 'passwordSecret',
    //   reason: 'invalid passwordSecret'
    // }))
  }
  const hmac = crypto.createHmac('sha256', secret.toString('ascii'))
  hmac.update(password)
  return {
    passwordHash: hmac.digest('hex'),
    version
  }
}

module.exports = encryptPwd
// export default encryptPwd
