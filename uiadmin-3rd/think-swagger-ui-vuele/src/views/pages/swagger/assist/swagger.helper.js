import { isEmpty, isEmptyObject } from 'tennetcn-ui/lib/utils'
export default {
  data() {
    return {
      methodForm: {
        requestProtocol: 'http://',
        contentType: null,
        requestPath: this.$route.query.path,
        requestMethod: this.activeName
      },
      responseResult: {},
      paramColumn: [
        { text: '启用', name: 'open', width: '60', editable: true, type: 'checkbox' },
        { text: '参数', name: 'name', width: '180', editable: true },
        { text: '值', name: 'value', editable: true, type: 'input' },
        { text: '描述', name: 'description', width: '180' },
        { text: '是否必填', name: 'required', width: '80' },
        { text: '参数类型', name: 'in', width: '120' },
        { text: '数据类型', name: 'type', width: '120' }
      ],
      simpleParamColumn: [
        { text: '启用', name: 'open', width: '50', editable: true, type: 'checkbox' },
        { text: '参数', name: 'name', width: '120', editable: true },
        { text: '值', name: 'value', editable: true, type: 'input' },
        { text: '描述', name: 'description', width: '120' },
        { text: '必填', name: 'required', width: '50' },
        { text: '参数类型', name: 'in', width: '80' },
        { text: '数据类型', name: 'type', width: '80' }
      ],
      customParamItem: {
        category: 'custom',
        disabled: null,
        in: 'query',
        name: '',
        editable: true,
        open: true,
        required: false,
        type: 'string'
      },
      customParam: [],
      responseTimeInfo: {
        requestTime: null,
        responseTime: null,
        diffTime: null
      }
    }
  },
  computed: {
    columns: function() {
      if (this.theme === 'admin') {
        return this.paramColumn
      } else if (this.theme === 'simple') {
        return this.simpleParamColumn
      }
    },
    menuInfo() {
      if (this.menus === null) {
        return null
      }
      const parentMenu = this.menus[this.$route.query.pindex]
      if (parentMenu === null) {
        return null
      }
      return parentMenu.children[this.$route.query.index]
    },
    tabs() {
      if (this.menuInfo === null) {
        return []
      }
      const reqMethod = this.menuInfo.reqMethod
      const tabs = Object.keys(reqMethod)

      this.activeName = tabs[0]
      return tabs
    },
    reqMethod() {
      if (this.menuInfo === null) {
        return {}
      }
      return this.menuInfo.reqMethod[this.activeName] || {}
    },
    producesProviders() {
      const produces = this.reqMethod.produces
      if (isEmpty(produces)) {
        return []
      }

      return produces.map((item, index) => {
        if (index === 0) {
          this.methodForm.contentType = item
        }
        return { text: item, value: item, id: index }
      })
    },
    parameters() {
      let params = (this.reqMethod.parameters || [])
      this.isPostJson = false
      params.forEach(item => {
        this.$set(item, 'open', true)
        this.$set(item, 'editable', false)
        if (!isEmpty(item.schema) && !isEmpty(item.schema.$ref)) {
          item.type = 'json'
          this.isPostJson = true
          const value = JSON.stringify(this.calcComplexParam(item), null, '  ')
          this.$set(item, 'value', value)
          this.$set(item, 'defaultValue', value)
          // schema description
          item.schemaDescription = this.calcComplexParamDescription(item)
        }
      })
      return params.concat(this.customParam)
    }
  },
  methods: {
    calcComplexParamDescription(item) {
      return this.calcComplexParam(item, true)
    },
    calcComplexParamResp() {
      const resps = this.menuInfo.reqMethod[this.activeName].responses
      return this.calcComplexParam(resps['200'], true)
    },
    calcComplexParam(item, isDesc) {
      var result = {}
      // 不是复杂属性
      if (isEmpty(item.schema) || isEmpty(item.schema.$ref)) {
        return result
      }
      this.loopCalcComplexParam(item.schema, result, isDesc)
      return result
    },
    loopCalcComplexParam(parentRefProperty, parentObj, isDesc) {
      if (!isEmpty(parentRefProperty.$ref)) {
        const ref = this.getDefinName(parentRefProperty.$ref)
        const refDefin = this.swaggerInfo.definitions[ref]

        for (var key in refDefin.properties) {
          const refProperty = refDefin.properties[key]
          if (refProperty.type === 'array') {
            var childArr = []
            this.loopCalcComplexParamArr(refProperty, childArr, isDesc)
            if (isDesc) {
              if (isEmpty(refProperty.items.$ref)) {
                parentObj[key] = [refProperty.description + '(' + refProperty.items.type + ')']
              } else {
                parentObj[key] = childArr
              }
            } else {
              parentObj[key] = childArr
            }
          } else {
            var childObj = {}
            // 继续计算子级
            this.loopCalcComplexParam(refProperty, childObj, isDesc)
            if (isDesc) {
              if (isEmpty(refProperty.type)) {
                parentObj[key] = childObj
              } else {
                parentObj[key] = refProperty.description + '(' + refProperty.type + ')'
              }
            } else {
              parentObj[key] = isEmptyObject(childObj) ? (refProperty.type === 'integer' ? 0 : '') : childObj
            }

          }
        }
      }
    },
    loopCalcComplexParamArr(parentRefProperty, parentArr, isDesc) {
      if (isEmpty(parentRefProperty.items.$ref)) {
        if (parentRefProperty.items.type === 'string') {
          parentArr.push('')
        } else {
          parentArr.push([])
        }
      } else {
        var childObj = {}
        // 继续计算子级
        this.loopCalcComplexParam(parentRefProperty.items, childObj, isDesc)

        parentArr.push(childObj)
      }
    },
    getDefinName(refFull) {
      return refFull.replace('#/definitions/', '')
    },
    tabName(name) {
      const reqMethod = this.menuInfo.reqMethod[name] || {}
      return reqMethod.summary + '-' + name
    }
  }
}
