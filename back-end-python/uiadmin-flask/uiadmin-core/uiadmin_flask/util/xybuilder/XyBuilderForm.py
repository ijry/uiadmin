from mergedeep import merge

class XyBuilderForm:
    formType = {
        'tabs': 'TABS切换',
        'hidden': '隐藏元素',
        'static': '静态文本',
        'link': '跳转链接',
        'text': '单行文本',
        'password': '密码',
        'url': 'URL网址',
        'email': '邮箱',
        'date': '日期',
        'number': '数字',
        'digit': '浮点型数字',
        'tel': '手机号',
        'textarea': '多行文本',
        'array': '自定义数组',
        'select': '下拉框',
        'selects': '下拉框多选',
        'radio': '单选框',
        'checkbox': '多选框',
        'switch': '开关',
        'slider': '滑块',
        'tags': '标签',
        'datepicker': '日期',
        'timepicker': '时刻',
        'datetimepicker': '时间',
        'daterangepicker': '日期区间',
        'datetimerangepicker': '时间区间',
        'rate': '星级评分',
        'cascader': '级联选择',
        'region': '省市区联动',
        'colorpicker': '颜色选择器',
        'image': '单图上传',
        'imageflex': '单图列表',
        'images': '多图上传',
        'file': '单文件上传',
        'files': '多文件上传',
        'poster': '分享海报',
        'selectlist': '列表选择器',
        'checkboxtree': '树状表格复选',
        'markdown': 'Markdown',
        'html': '富文本',
        'tinymce': 'TinyMCE富文本',
        'sku': '商品规格',
        'fee': '运费模板'
    }

    def init(self):
        self.data = {
            'alertList': {
                'top': [],
                'bottom': []
            },
            'formMethod': 'post',
            'formCols': [], # 表单项目分栏
            'formGroups': [], # 表单项目分组
            'formItems': [],
            'formValues': [],
            'formRules': [],
            'formTabs': [],
            'config': {
                'continue': False, # 显示继续添加
                'itemDefaultPosition': '',
                'submitButtonTitle': '确认',
                'cancelButtonTitle': '取消',
                'footerButtonLength': '120px',
                'labelPosition': 'left',
                'labelWidth': '100px',
                'defaultUploadDriver': "",
                'defaultUploadAction': '/api/v1/core/upload/upload',
                'defaultUploadMaxSize': 512
            }
        }
        return self

    # /**
    #  * 设置配置
    #  * @author jry <ijry@qq.com>
    #  */
    def setConfig(self, name, value):
        self.data['config'][name] = value
        return self

    # /**
    #  * 设置配置
    #  * @author jry <ijry@qq.com>
    #  */
    def setConfig(self, name, value):
        self.data['config'][name] = value
        return self

    # /**
    #  * 添加顶部提醒
    #  * @author jry <ijry@qq.com>
    #  */
    def addAlertItem(self, layer, item):
        self.data['alertList'][layer].push(item)
        return self

    # /**
    #  * 设置tab
    #  * @author jry <ijry@qq.com>
    #  */
    def addFormTab(self, tab):
        self.data['formTabs'].append(tab)
        return self

    # /**
    #  * 设置提交方法
    #  * @author jry <ijry@qq.com>
    #  */
    def setFormMethod(self, method = 'post'):
        self.data['formMethod'] = method
        return self

    # /**
    #  * 构造表单项
    #  * @author jry <ijry@qq.com>
    #  */
    def getFormItem(
        self,
        name,
        title,
        type = 'text',
        value = '' ,
        extra = {}
    ):
        item = {}
        item['name'] = name
        item['title'] = title
        item['type'] = type
        item['value'] = value
        item['extra'] = extra
        return item
    
    # /**
    #  * 添加表单分组
    #  * @author jry <ijry@qq.com>
    #  */
    def addFormCol(
        self,
        name,
        span = [],
        itemList = [],
        extra = []
    ):
        self.data['formCols'].append({
            'name': name,
            'span': span,
            'itemList': itemList,
            'extra': extra
        })
        return self

    # /**
    #  * 添加表单分组
    #  * @author jry <ijry@qq.com>
    #  */
    def addFormGroup(
        self,
        name,
        title,
        itemList = [],
        extra = {}
    ):
        self.data['formGroups'].append({
            'name': name,
            'title': title,
            'itemList': itemList,
            'extra': extra
        })
        return self

    # /**
    #  * 添加表单项目
    #  * @author jry <ijry@qq.com>
    #  */
    def addFormItem(
        self,
        name,
        title,
        type = 'text',
        value = '' ,
        extra = {}
    ):
        item = self.getFormItem(name, title, type, value, extra)
        if (len(self.data['formTabs']) > 0):
            self.data['formTabs'][(self.data['formTabs']).length - 1]['formItems'].append(item)
            self.data['formTabs'][(self.data['formTabs']).length - 1]['formRules']['default'] = []
        else:
            self.data['formItems'].append(item)
            # self.data['formRules']['default'] = []
        return self

    # /**
    #  * 添加表单验证
    #  * @author jry <ijry@qq.com>
    #  */
    def addFormRule(self, name, rule):
        if (len(self.data['formTabs']) > 0):
            self.data['formTabs'][len(self.data['formTabs']) - 1]['formRules'][name] = rule
        else:
            self.data['formRules'][name] = rule
        return self

    # /**
    #  * 设置表单数据
    #  * @author jry <ijry@qq.com>
    #  */
    def setFormValues(self, data = {}):
        self.data['itemValues'] = data
        return self

    # /**
    #  * 返回数据
    #  * @author jry <ijry@qq.com>
    #  */
    def getData(self):
        return self.data
