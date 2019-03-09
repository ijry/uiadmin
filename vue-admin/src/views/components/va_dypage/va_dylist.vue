<style>
    .spin-icon-load{
        animation: ani-spin 1s linear infinite;
    }
    @keyframes ani-spin {
        from { transform: rotate(0deg);}
        50%  { transform: rotate(180deg);}
        to   { transform: rotate(360deg);}
    }
    .spin-col {
        height: 100px;
        position: relative;
        border: 0px solid #eee;
    }
</style>

<template>
  <div>
    <Card shadow>
        <template v-if="this.data_list.length > 0">
            <template v-for="(item,key) in list_data.top_button_list">
                <Modal :scrollable="item.page_data.scrollable" :draggable="item.page_data.draggable" :ref="'top_modal_' + key" :key="'top_modal_' + key" v-model="item.page_data.show":width="item.page_data.width?item.page_data.width:600" :title="item.page_data.title">
                    <VaDyform :ref="'top_form_' + key" :api="item.page_data.api_blank" :foot_hide="true"></VaDyform>
                    <div slot="footer" style="text-align: left;">
                        <Button :loading="item.page_data.loading" type="primary" size="large" style="margin-right: 15px" @click="btnSubmit('top', key)">确认提交</Button>
                        <Button type="text" size="large" @click="btnCancel('top', key)">取消操作</Button>
                    </div>
                </Modal>
            </template>
            <Button
                v-for="(item,key) in list_data.top_button_list"
                :key="'button' + key"
                empty-text="当前没有数据"
                :type="item.style.type?item.type:'default'"
                :shape="item.style.shape?item.shape:'circle'"
                :size="item.style.size?item.size:'default'"
                :icon="item.style.icon?item.icon:' '"
                @click="top_button_modal(key)"
                style="margin-bottom: 15px;">
                {{item.title}}
            </Button>
            <!-- https://github.com/lison16/tree-table-vue -->
            <tree-table
                expand-key="title"
                :is-fold="false"
                :border="true"
                :stripe="true"
                :expand-type="false"
                :selectable="false"
                :columns="list_data.columns"
                :data="this.data_list" >
                <template slot="right_button_list" slot-scope="scope">
                    <Button
                        v-for="(item,key) in list_data.right_button_list"
                        :key="'button' + key"
                        :type="item.style.type?item.type:'default'"
                        :shape="item.style.shape?item.shape:'circle'"
                        :size="item.style.size?item.size:'default'"
                        :icon="item.style.icon?item.icon:' '"
                        @click="right_button_modal(key, scope)"
                        style="margin-right: 3px;">
                        {{item.title}}
                    </Button>
                </template>
            </tree-table>
            <template v-for="(item,key) in list_data.right_button_list">
                <template v-if="item.page_data.modal_type == 'form'">
                    <Modal :scrollable="item.page_data.scrollable" :draggable="item.page_data.draggable" :key="'modal' + key" v-model="item.page_data.show" :width="item.page_data.width?item.page_data.width:600" :title="item.page_data.title">
                        <VaDyform :ref="'right_form_' + key" :api="item.page_data.api_blank" :foot_hide="true"></VaDyform>
                        <div slot="footer" style="text-align: left;">
                            <Button :loading="item.page_data.loading" type="primary" size="large" style="margin-right: 15px" @click="btnSubmit('right', key)">确认提交</Button>
                            <Button type="text" size="large" @click="btnCancel('right', key)">取消操作</Button>
                        </div>
                    </Modal>
                </template>
                <template v-else-if="item.page_data.modal_type == 'list'">
                    <Modal :scrollable="item.page_data.scrollable" :draggable="item.page_data.draggable" :key="'modal' + key" v-model="item.page_data.show" :width="item.page_data.width?item.page_data.width:600" :title="item.page_data.title">
                        <DynamicList :ref="'right_form_' + key" :api="item.page_data.api_blank" :foot_hide="true"></DynamicList>
                        <div slot="footer" style="text-align: left;">
                            <Button :loading="item.page_data.loading" type="primary" size="large" style="margin-right: 15px" @click="btnSubmit('right', key)">确认提交</Button>
                            <Button type="text" size="large" @click="btnCancel('right', key)">取消操作</Button>
                        </div>
                    </Modal>
                </template>
            </template>
        </template>
        <template v-else>
            <Row>
                <Col class="spin-col" span="24">
                    <Spin fix>
                        <Icon type="ios-loading" size=22 class="spin-icon-load"></Icon>
                        <div>Loading</div>
                    </Spin>
                </Col>
            </Row>
        </template>
        <div style="margin-top: 15px;text-align: right;font-size: 12px;color: #808695;transform: scale(0.8)">via iadypage</div>
    </Card>
  </div>
</template>

<script>
import VaDyform from '@/views/components/va_dypage/va_dyform'
export default {
    name: 'DynamicList',
    props: {
        api: ''
    },
    components: {
        VaDyform
    },
    data () {
        return {
            data_list: [],
            list_data: {
                top_button_list: {},
                right_button_list: {},
            }
        }
    },
    watch: {
        api(val) {
            this.loadData()
        }
    },
    created() {
        this.loadData()
    },
    beforeMount () {
    },
    mounted(){
    },
    beforeUpdate () {
    },
    updated () {
    },
    beforeDestroy () {
    },
    destroyed () {
    },
    methods: {
        btnSubmit(cate, key) {
            this.list_data[cate + '_button_list'][key].page_data.loading = true
            this.$refs[cate + '_form_' + key][0].submit()
            this.list_data[cate + '_button_list'][key].page_data.loading = false
        },
        btnCancel(cate, key) {
            this.list_data[cate + '_button_list'][key].page_data.loading = true
            this.$refs[cate + '_form_' + key][0].cancel()
            this.list_data[cate + '_button_list'][key].page_data.show = false
            this.list_data[cate + '_button_list'][key].page_data.loading = false
        },
        loadData (api = ''){
            if (api != '') {
                this.api = api
            }
            if (this.api) {
                let _this = this
                axios.get(this.api)
                    .then(function (res) {
                        res = res.data
                        if (res.code == '200') {
                            //console.log(res)
                            _this.list_data = res.data.list_data
                            _this.data_list = res.data.data_list
                        } else {
                            _this.$Message.error(res.msg)
                        }
                    })
                    .catch(function (error) {
                        console.log(error)
                    });
            }
        },
        top_button_modal(key) {
            this.list_data.top_button_list[key].page_data.api_blank = this.list_data.top_button_list[key].page_data.api
            this.list_data.top_button_list[key].page_data.show = true
        },
        right_button_modal(key, scope) {
            let _this = this
            let button_data = _this.list_data.right_button_list[key]
            var api_suffix = ''
            if (_this.list_data.right_button_list[key].page_data.api_suffix) {
                let asd = _this.list_data.right_button_list[key].page_data.api_suffix
                for(let v of asd) {
                    api_suffix = api_suffix + '/' + scope.row[v]
                };
            } else {
                api_suffix = '/' + scope.row.id
            }
            if (button_data.page_data.page_type == 'replace') {
                _this.$router.replace({
                    path: button_data.page_data.route + '/' + scope.row.name
                })
            } else {
                switch (button_data.page_data.modal_type) {
                    case 'confirm':
                        _this.$Modal.confirm({
                            okText: button_data.page_data.okText,
                            cancelText: button_data.page_data.cancelText,
                            title: button_data.page_data.title,
                            content: button_data.page_data.content,
                            onOk: () => {
                                axios.delete(button_data.page_data.api + api_suffix)
                                    .then(function (res) {
                                        res = res.data
                                        if(res.code=='200'){
                                            _this.$Message.success(res.msg)
                                        }else{
                                            _this.$Message.error(res.msg)
                                        }
                                    })
                                    .catch(function (error) {
                                        console.log(error)
                                    });
                            },
                            onCancel: () => {
                            }
                        });
                        break;
                    case 'list':
                        _this.list_data.right_button_list[key].page_data.api_blank
                            = _this.list_data.right_button_list[key].page_data.api + api_suffix
                        _this.list_data.right_button_list[key].page_data.show = true
                        break;
                    default:
                        _this.list_data.right_button_list[key].page_data.api_blank
                            = _this.list_data.right_button_list[key].page_data.api + api_suffix
                        _this.list_data.right_button_list[key].page_data.show = true
                        break;
                }
            }
        }
    }
}
</script>
