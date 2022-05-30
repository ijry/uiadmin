<template>
  <div class="login-container">
    <div class="login-card" :class="{url:visitMode==='url',json:visitMode==='json'}">
      <tc-form class="login-form" label-width="120px">
        <div class="title-container">
          <h3 class="title">swagger-ui</h3>
        </div>
        <div class="login-form-content">
          <tc-form-item v-show="visitMode==='url'" label="swagger路径" style="margin-bottom:30px;">
            <tc-input v-model="swaggerPath" placeholder="swagger path" @keyup.native.enter="login" auto-complete="off"/>
          </tc-form-item>
          <tc-form-item v-show="visitMode==='json'" label="json数据" style="">
            <tc-input type="textarea"  :rows="15" v-model="swaggerJson" placeholder="swagger json" auto-complete="off"/>
          </tc-form-item>
          <tc-form-item label="主题" style="margin-bottom:30px;">
            <tc-radio-group v-model="theme" :providers="themeProviders" />
          </tc-form-item>
          <el-button-group class="loginButton">
            <tc-button style="width:50%" :loading="loading" @click="login">访问</tc-button>
            <tc-button style="width:50%" type="default" @click="modeChange">{{modeText}}</tc-button>
          </el-button-group>
        </div>
      </tc-form>
    </div>
    <div class="copyright-info">
      <a href="https://github.com/chfree/think-swagger-ui-vuele" target="_blank">think-swagger-ui-vue</a> powered by <a href="https://github.com/chfree" target="_blank">chfree</a> develop
    </div>
  </div>
</template>

<script>
import swaggerService from '@/api/swagger'
import { writeFileDown, mimeType } from '@/components/util/file/down'

export default {
  data() {
    return {
      visitMode: 'url',
      host: '',
      path: 'doc.json',
      swaggerPath: '',
      swaggerJson: null,
      loading: false,
      theme: 'admin',
      themeProviders: [{id: '1', text: 'admin', value: 'admin'}, {id: '2', text: 'simple', value: 'simple'}]
    }
  },
  created() {
    this.host = window.location.protocol + '//' + window.location.host
    let pathname = window.location.pathname.replace('/thinkswagger/index.html', '')
    if (pathname === '/') {
      pathname = ''
    }
    this.host += pathname

    this.swaggerPath = this.host + this.path
  },
  computed: {
    modeText: function() {
      return this.visitMode === 'json' ? 'url模板' : 'JSON模式'
    }
  },
  methods: {
    testDwon() {
      console.log()
      writeFileDown('## 测试 \r\n', 'test.md', {type: mimeType.md})
    },
    modeChange() {
      this.visitMode = this.visitMode === 'json' ? 'url' : 'json'
    },
    login() {
      this.$store.commit('theme', this.theme)
      window.sessionStorage.theme = this.theme
      var path = '/main/index'
      if (this.theme === 'simple') {
        path = '/simpleMain/index'
      }
      console.log('this.visitMode', this.visitMode)
      if (this.visitMode === 'url') {
        this.resolveUrl(path)
      } else if (this.visitMode === 'json') {
        this.resolveJson(path)
      }
    },
    resolveUrl(path) {
      swaggerService.reqAndResolveSwagger(this.swaggerPath).then(result => {
        if (result.openapi !== undefined) {
          this.$router.push({ path: path })
        } else {
          // alert('出错');
        }
      })
    },
    resolveJson(path) {
      swaggerService.resolveSwagger(this.swaggerJson).then(result => {
        if (result.openapi !== undefined) {
          this.$router.push({ path: path })
        }
      })
    }
  }
}
</script>

<style lang="scss">
$bg:#283443;
.login-container{
  background-color: $bg;
  height: 100vh;
}
$login-card-width:600px;
$login-card-height:280px;
.login-card{
  position: absolute;
  width: $login-card-width;
  background-color: #fff;
  border:1px solid #333;
  border-radius: 5px;
  left: 50%;
  top: 45%;
  margin-left: -$login-card-width/2;
  
}

.login-card.url{
  height: $login-card-height;
  margin-top: -$login-card-height/2;
}
$login-card-height-json:550px;
.login-card.json{
  height: $login-card-height-json;
  margin-top: -$login-card-height-json/2;
}

 .title-container {
  .title {
    font-size: 26px;
    margin: 10px auto 30px auto;
    text-align: center;
    font-weight: bold;
  }
}
.login-form{
  .login-form-content{
    padding: 0px 40px 0px 0px;
    .loginButton{
      width:calc(100% - 120px);
      margin-bottom:10px;
      margin-left: 120px;
    }
  }
}

.copyright-info{
  color: rgb(255, 255, 255);
  position: fixed;
  bottom: 20px;
  width: $login-card-width;
  display: block;
  left: 50%;
  margin-left: -$login-card-width/2;
  text-align: center;
}
</style>