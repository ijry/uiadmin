const createConfig = require('uni-config-center')
const shareConfig = createConfig({ // 获取配置实例
    pluginId: 'uiadmin' ,// common/uni-config-center下的插件配置目录名
	defaultConfig: { // 默认配置
		defaultUploadSpaceId: '',
		defaultUploadClientSecret: '',
	}
})
const config = shareConfig.config() 

class XyBuilderForm {

    constructor() {
        this.data = {}
    }

    $formType = {
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
    };

    /**
     * 初始化
     * @author jry <ijry@qq.com>
     */
    init() {
        this.data = {
            'alertList': {
                'top': [],
                'bottom': []
            },
            'formMethod': 'post',
            'formCols': [], // 表单项目分栏
            'formGroups': [], // 表单项目分组
            'formItems': [],
            'formValues': [],
            'formRules': [],
            'formTabs': [],
            'config': {
                'continue': false, // 显示继续添加
                'itemDefaultPosition': '',
                'submitButtonTitle': '确认',
                'cancelButtonTitle': '取消',
                'footerButtonLength': '120px',
                'labelPosition': 'left',
                'labelWidth': '100px',
                'defaultUploadDriver': "uniCloud",
				'defaultUploadSpaceId': config.defaultUploadSpaceId,
				'defaultUploadClientSecret': config.defaultUploadClientSecret,
                'defaultUploadAction': '/api/v1/core/upload/upload',
                'defaultUploadMaxSize': 512
            }
        };
        return this;
    }

    /**
     * 设置配置
     * @author jry <ijry@qq.com>
     */
    setConfig($name, $value) {
        this.data['config'][$name] = $value;
        return this;
    }

    /**
     * 添加顶部提醒
     * @author jry <ijry@qq.com>
     */
    addAlertItem($layer, $item) {
        this.data['alertList'][$layer].push($item);
        return this;
    }

    /**
     * 设置tab
     * @author jry <ijry@qq.com>
     */
    addFormTab($tab) {
        this.data['formTabs'].push($tab);
        return this;
    }

    /**
     * 设置提交方法
     * @author jry <ijry@qq.com>
     */
    setFormMethod($method = 'post') {
        this.data['formMethod'] = $method;
        return this;
    }

    /**
     * 构造表单项
     * @author jry <ijry@qq.com>
     */
    getFormItem(
        $name,
        $title,
        $type = 'text',
        $value = '' ,
        $extra = []
    ) {
        let $item = {};
        $item['name'] = $name;
        $item['title'] = $title;
        $item['type'] = $type;
        $item['value'] = $value;
        $item['extra'] = $extra;
        return $item;
    }
    
    /**
     * 添加表单分组
     * @author jry <ijry@qq.com>
     */
    addFormCol(
        $name,
        $span = [],
        $itemList = [],
        $extra = []
    ) {
        this.data['formCols'].push({
            'name': $name,
            'span': $span,
            'itemList': $itemList,
            'extra': $extra
        });
        return this;
    }

    /**
     * 添加表单分组
     * @author jry <ijry@qq.com>
     */
    addFormGroup(
        $name,
        $title,
        $itemList = [],
        $extra = []
    ) {
        this.data['formGroups'].push({
            'name': $name,
            'title': $title,
            'itemList': $itemList,
            'extra': $extra
        });
        return this;
    }

    /**
     * 添加表单项目
     * @author jry <ijry@qq.com>
     */
    addFormItem(
        $name,
        $title,
        $type = 'text',
        $value = '' ,
        $extra = []
    ) {
        let $item = this.getFormItem($name, $title, $type, $value, $extra);
        if ((this.data['formTabs']).length > 0) {
            this.data['formTabs'][(this.data['formTabs']).length - 1]['formItems'].push($item);
            this.data['formTabs'][(this.data['formTabs']).length - 1]['formRules']['default'] = [];
        } else {
            this.data['formItems'].push($item);
            // this.data['formRules']['default'] = [];
        }
        return this;
    }

    /**
     * 添加表单验证
     * @author jry <ijry@qq.com>
     */
    addFormRule($name, $rule){
        if ((this.data['formTabs']).length > 0) {
            this.data['formTabs'][(this.data['formTabs']).length - 1]['formRules'][$name] = $rule;
        } else {
            this.data['formRules'][$name] = $rule;
        }
        return this;
    }

    /**
     * 设置表单数据
     * @author jry <ijry@qq.com>
     */
    setFormValues($data = []) {
        this.data['itemValues'] = $data;
        return this;
    }

    /**
     * 返回数据
     * @author jry <ijry@qq.com>
     */
   getData() {
        return this.data;
    }
}

module.exports = XyBuilderForm
