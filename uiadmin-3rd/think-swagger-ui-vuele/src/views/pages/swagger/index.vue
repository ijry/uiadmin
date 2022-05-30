<template>
  <div>
    <el-tabs v-model="activeName" type="border-card">
      <el-tab-pane v-for="name in tabs" :label="tabName(name)" :name="name" :key="name">
        <!-- <div class="swagger-title">{{reqMethod.summary}}</div> -->
        <el-divider>请求</el-divider>
        <tc-form label-width="150px" size="small">
           <el-row>
            <el-col :span="12">
              <tc-form-item label="请求地址">
                <tc-input v-model="methodForm.requestPath" readonly />
              </tc-form-item>
            </el-col>
            <el-col :span="12">
              <tc-form-item label="请求方式">
                <tc-input :value="activeName" readonly />
              </tc-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="12">
              <tc-form-item label="响应Content-Type">
                <tc-select v-model="methodForm.contentType" :providers="producesProviders" />
              </tc-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col>
              <el-row>
                <el-col :span="12">
                  <div style="margin-bottom:10px;margin-top:10px;">
                    <tc-button v-if="!isPostJson" type="think" @click="addParam" size="small">新增</tc-button>
                    <tc-button type="think" @click="selMdShow" size="small">查看md</tc-button>
                  </div>
                </el-col>
              </el-row>
              <tc-edit-table editmode="multi" :data="parameters" :columns="paramColumn">
                <template slot="editable" slot-scope="{ value, columnName, rowData, column, scope }"> 
                  <div v-if="columnName === 'value'">
                    <div v-if="rowData.type === 'json'">
                      <tc-button type="think" size="mini" @click="formatJson(value)">格式化编辑</tc-button>
                      <tc-input :rows="8" v-model="scope.row[columnName]" type="textarea" style="margin-top:5px;" clearable size="mini"></tc-input>
                    </div>
                    <tc-input v-else v-model="scope.row[columnName]" type="text" clearable size="mini"></tc-input>
                  </div>
                  <div v-else-if="columnName === 'name'">
                    <tc-input v-if="rowData['category'] === 'custom'" v-model="scope.row[columnName]" type="text" clearable size="mini"></tc-input>
                    <span v-else>{{value}}</span>
                  </div>
                </template>
                <template slot-scope="{ value, columnName, rowData, column, scope }"> 
                  <div v-if="columnName === 'description'">
                    <span v-if="rowData.type === 'json'">
                      <span>
                        <tc-button type="text" @click="openReqJsonView(rowData)">{{value}}</tc-button>
                      </span>
                    </span>
                    <span v-else>
                      {{value}}
                    </span>
                  </div>
                </template>
              </tc-edit-table>
            </el-col>
          </el-row>
          <el-row style="margin-top:20px;text-align:center;">
            <el-col>
              <tc-button type="think" size="small" @click="sendRequest">试一试</tc-button>
              <tc-button type="think" size="small" @click="fillData">填充数据</tc-button>
              <tc-button type="think" size="small" @click="resetData">重置</tc-button>
            </el-col>
          </el-row>
        </tc-form>
        <el-divider>响应</el-divider>
        <tc-block>
          <el-row>
            <el-col :span="3">
              请求时间
            </el-col>
            <el-col :span="5">
              <span>{{responseTimeInfo.requestTime}}</span>
            </el-col>
            <el-col :span="3">
              响应时间
            </el-col>
            <el-col :span="5">
              <span>{{responseTimeInfo.responseTime}}</span>
            </el-col>
            <el-col :span="3">
              相差毫秒
            </el-col>
            <el-col :span="5">
              <span>{{responseTimeInfo.diffTime}}</span>
            </el-col>
          </el-row>
        </tc-block>
        <el-tabs v-model="respActiveName">
          <el-tab-pane label="响应数据" name="resp">
              <json-viewer v-if="responseResult"
                :value="responseResult"
                :expand-depth=5
                copyable
                boxed
                sort></json-viewer>
          </el-tab-pane>
          <el-tab-pane label="示例描述" name="respDesc">
            <json-viewer v-if="respJson"
                :value="respJson"
                :expand-depth=5
                copyable
                boxed
                sort></json-viewer>
          </el-tab-pane>
        </el-tabs>
      </el-tab-pane>
    </el-tabs>
    <tc-dialog loading title="编辑json" :visible.sync="jsonEditForm.show" width="800px" height="600px">
      <jsonedit :json="jsonEditForm.json" @save-json="saveJson"/>
    </tc-dialog>
    <tc-dialog loading title="查看md" :visible.sync="mdShowForm.show" width="90%" height="90%">
      <md-show :mdContent="mdShowForm.content" />
    </tc-dialog>
     <tc-dialog loading title="查看请求" :visible.sync="reqJsonView.show" width="800px" height="600px">
      <req-json-view :json="reqJsonView.json" />
    </tc-dialog>
  </div>
</template>

<script>
import { isEmpty } from 'tennetcn-ui/lib/utils'
import { mapGetters } from 'vuex'
import mock from 'mockjs'
import swaggerService from '@/api/swagger'
import jsonViewer from 'vue-json-viewer'
import jsonedit from './jsonedit'
import swaggerHelper from './assist/swagger.helper'
import buildmd from './assist/buildmd'
import mdShow from './mdShow'
import reqJsonView from './reqJsonView'

export default {
  mixins: [swaggerHelper, buildmd],
  components: { jsonViewer, jsonedit, mdShow, reqJsonView },
  data() {
    return {
      respActiveName: 'resp',
      jsonEditForm: {
        show: false,
        json: null
      },
      mdShowForm: {
        show: false,
        content: null
      },
      reqJsonView: {
        show: false,
        json: null
      },
      isPostJson: false,
      activeName: '',
      respJson: {}
    }
  },
  mounted() {
  },
  watch: {
    '$route.query.path': function(newVal) {
      this.methodForm.requestPath = newVal
      this.responseResult = {}
      this.customParam = []
      this.respActiveName = 'resp'
      this.resetResponseTime()
    },
    'activeName': function(newVal) {
      this.methodForm.requestMethod = newVal
      this.respActiveName = 'resp'
      this.resetResponseTime()
    },
    'respActiveName': function(newVal) {
      this.respJson = {}
      if (newVal === 'respDesc') {
        this.respJson = this.calcComplexParamResp()
      }
    }
  },
  computed: {
    ...mapGetters([
      'menus',
      'swaggerInfo',
      'theme'
    ])
  },
  methods: {
    openReqJsonView(rowData) {
      this.reqJsonView.show = true
      this.reqJsonView.json = rowData.schemaDescription
    },
    selMdShow() {
      this.mdShowForm.show = true
      this.mdShowForm.content = this.buildMd()
    },
    resetResponseTime() {
      this.responseTimeInfo.requestTime = null
      this.responseTimeInfo.responseTime = null
      this.responseTimeInfo.diffTime = null
    },
    formatJson(json) {
      this.jsonEditForm.json = json
      this.jsonEditForm.show = true
    },
    saveJson(json) {
      console.log('json', json)
      this.$set(this.parameters[0], 'value', JSON.stringify(json))
      this.jsonEditForm.show = false
    },
    addParam() {
      this.customParam.push(Object.assign({}, this.customParamItem))
    },
    sendRequest() {
      this.respActiveName = 'resp'
      const basePath = this.swaggerInfo.basePath === '/' ? '' : this.swaggerInfo.basePath
      let requestUrl = this.methodForm.requestProtocol + this.swaggerInfo.host + basePath + this.methodForm.requestPath
      var method = this.methodForm.requestMethod
      var requestData = {}
      if (this.isPostJson) {
        method = 'postJson'
        requestData = JSON.parse(this.parameters[0].value)
      } else {
        this.parameters.forEach(item => {
          if (item.open && !isEmpty(item.name)) {
            requestData[item.name] = item.value === undefined ? null : item.value
          }
        })
      }
      const reqData = new Date()
      this.responseTimeInfo.requestTime = this.$moment.formatDateTime(reqData)
      requestUrl = this.urlFormat(requestUrl, requestData)
      swaggerService.sendRequest(method, requestUrl, requestData).then(result => {
        const respData = new Date()
        this.responseTimeInfo.responseTime = this.$moment.formatDateTime(respData)
        this.responseTimeInfo.diffTime = respData.getTime() - reqData.getTime()
        this.responseResult = result.data
      })
    },
    urlFormat(url, params) {
      if (!new RegExp('\\{(.*?)\\}').test(url)) {
        return url
      }
      const pathVars = url.match(new RegExp('\\{(.*?)\\}', 'g'))
      for (var key of pathVars) {
        url = url.replace(new RegExp(key, 'g'), params[key.match(/{(.*?)}/)[1]])
      }
      return url
    },
    fillData() {
      const random = mock.Random
      this.parameters.forEach(item => {
        if (isEmpty(item.name)) {
          return true
        }
        if (item.type === 'string') {
          this.$set(item, 'value', random.word(1, 10))
        } else if (item.type === 'integer' || item.type === 'int') {
          this.$set(item, 'value', random.integer(1, 99))
        }
      })
    },
    resetData() {
      this.customParam = []
      this.parameters.forEach(item => {
        this.$set(item, 'value', item.defaultValue)
      })
      this.responseResult = {}
      this.resetResponseTime()
    }
  }
}
</script>

<style lang="scss">
.jv-node {
  span.jv-toggle.open{
    transform: rotate(90deg) !important;
  }
}
.jv-container .jv-code {
  overflow: auto;
}
</style>