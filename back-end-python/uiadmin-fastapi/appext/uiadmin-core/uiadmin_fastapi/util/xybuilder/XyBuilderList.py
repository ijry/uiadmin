from mergedeep import merge

class XyBuilderList:
    def init(self):
        self.data = {
            'alertList': {
                'top': [],
                'bottom': []
            },
            'dataList': [],
            'dataListParams': {
                'expandKey': 'title',
                'tableName': '',
                'selectable': True,
                'selectType': 'checkbox'
            },
            'topButtonList': [],
            'rightButtonList': [],
            'columns': [],
            'dataPage': {
                'total': 0,
                'limit': 0,
                'page': 0
            },
            'filterItems': [],
            'filterValues': [],
            'filterExtra': [],
            'countList': [],
            'config': {
                'listExpandAll': False,
                'modalDefaultWidth': '800px',
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
    #  * 添加顶部按钮
    #  * @author jry <ijry@qq.com>
    #  */
    def addAlertItem(self, layer, item):
        self.data['alertList'][layer].push(item)
        return self

    # /**
    #  * 添加顶部按钮
    #  * @author jry <ijry@qq.com>
    #  */
    def addTopButton(self, name, title, pageData = {}, style = {}):
        btn = {}
        btn['name'] = name
        btn['title'] = title
        btn['pageData'] = {
            'show': False,
            'pageType': 'modal', # 支持modal和page
            'modalType': 'form',
            'modalClosable': False,
        }
        btn['pageData'] = merge(btn.get('pageData'), pageData)
        # if (btn['pageData']['path'] && btn['pageData']['path'] == '') {
        #     btn['pageData']['path'] = ltrim(btn['pageData']['api'], '/v1');
        # }
        btn['style'] = style
        self.data['topButtonList'].append(btn)
        return self

    # /**
    #  * 添加右侧按钮
    #  * @author jry <ijry@qq.com>
    #  */
    def addRightButton(self, name, title, pageData = [], style = []):
        btn = {}
        btn = self.getRightButton(name, title, pageData, style)
        self.data['rightButtonList'].append(btn)
        return self

    # /**
    #  * 构造右侧按钮
    #  * @author jry <ijry@qq.com>
    #  */
    def getRightButton(self, name, title, pageData = [], style = []):
        btn = {}
        btn['name'] = name
        btn['title'] = title
        btn['pageData'] = {
            'show': False,
            'pageType': 'page', # 支持modal和page
            'modalType': 'form',
            'modalClosable': False,
        }
        btn['pageData'] = merge(btn.get('pageData'), pageData)
        # 暂未启用，本打算在pageType=page模式下将URL优化减去v1/admin等前缀的，
        # 因为之前按钮定义的api字段都会直接带有/v1/api/admin，后来因为iadmin、
        # eadmin等前缀太多没启用此规范（定义按钮时没有将api拆成跟接口一样的apiPrefix、
        # menuLayer、path三个变量主要是考虑到通用性，可以请求非本系统的接口）
        # if (btn['pageData']['path'] && btn['pageData']['path'] == '') {
            # btn['pageData']['path'] = ltrim(btn['pageData']['api'], '/v1');
        # }
        btn['style'] = style
        return btn

    # /**
    #  * 批量添加顶部按钮
    #  * @author jry <ijry@qq.com>
    #  */
    def addTopButtons(self, buttons):
        for value in buttons:
            self.addTopButton(value['name'], value['title'], value['pageData'], value['style'])
        return self

    # /**
    #  * 批量添加右侧按钮
    #  * @author jry <ijry@qq.com>
    #  */
    def addRightButtons(self, buttons):
        for value in buttons:
            self.addRightButton(value['name'], value['title'], value['pageData'], value['style'])
        return self

    # /**
    #  * 批量添加表格列
    #  * @author jry <ijry@qq.com>
    #  */
    def addColums(self, columns):
        for value in columns:
            self.addColumn(value['key'], value['title'], value['data'])
        return self

    # /**
    #  * 添加表格列
    #  * @author jry <ijry@qq.com>
    #  */
    def addColumn(self,name, title, data = {}):
        column = {
            'name': name,
            'title': title,
            'extra': {
                'type': '',
                'width': '',
                'minWidth': '',
                'show': True,
                'loading': False,
                'options': [],
                'extend': []
            }
        }
        if (data.get('template')):
            data['type'] = data['template']
            # unset(data['template'])
        column['extra'] = merge(column['extra'], data)
        self.data['columns'].append(column)
        return self

    # /**
    #  * 设置列表数据
    #  * @author jry <ijry@qq.com>
    #  */
    def setDataList(self, dataList):
        self.data['dataList'] = dataList
        return self

    # /**
    #  * 设置分页
    #  * @author jry <ijry@qq.com>
    #  */
    def setDataPage(self,total, limit = 10, page = 1):
        self.data['dataPage'] = {
            'total': total,
            'limit': limit,
            'page': page
        };
        return self

    # /**
    #  * 设置展开字段
    #  * @author jry <ijry@qq.com>
    #  */
    def setExpandKey(self, expandKey):
        self.data['dataListParams']['expandKey'] = expandKey
        return self;

    # /**
    #  * 设置数据表名
    #  * @author jry <ijry@qq.com>
    #  */
    def setTableName(self, tableName):
        self.data['dataListParams']['tableName'] = tableName;
        return self;

    # /**
    #  * 设置选择
    #  * @author jry <ijry@qq.com>
    #  */
    def setSelectType(self, selectType = 'checkbox'):
        if selectType:
            self.data['dataListParams']['selectable'] = True
            self.data['dataListParams']['selectType'] = selectType
        else:
            self.data['dataListParams']['selectable'] = False
        return self

    # /**
    #  * 批量添加搜索
    #  * @author jry <ijry@qq.com>
    #  */
    def addFilterItems(self, list):
        for v in list:
            if len(v) > 1:
                self.addFilterItem(v[0], v[1], v[2], v[3], v[4]);
        return self

    # /**
    #  * 添加搜索
    #  * @author jry <ijry@qq.com>
    #  */
    def addFilterItem(
        self,
        name,
        title,
        type = 'text',
        value = '' ,
        extra = []
    ):
        item = {};
        item['name'] = name;
        item['title'] = title;
        item['type'] = type;
        item['value'] = value;
        item['extra'] = extra;
        self.data['filterItems'].append(item);
        return self

    # /**
    #  * 筛选功能其他项目
    #  * @author jry <ijry@qq.com>
    #  */
    def setFilterExtra(self, item):
        self.data['filterExtra'] = item;
        return self

    # /**
    #  * 添加一个统计
    #  * @author jry <ijry@qq.com>
    #  */
    def addCount(
        self,
        item = {'title': '', 'icon': 'xyicon xyicon-my', 'bgColor': '#f8f8f8'},
        current = {'value': 0, 'suffix': ''},
        content = {'value': '', 'list': []}
    ):
        self.data['countList'].append({
            'item': item,
            'current': current,
            'content': content
        });
        return self

    # /**
    #  * 返回数据
    #  * @author jry <ijry@qq.com>
    #  */
    def getData(self):
        return self.data
