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
    <div class="form-wrapper">
        <template v-if="this.data != ''">
            <Form @submit.native.prevent :ref="ref" :model="data.form_values" :label-position="label_position" :label-width="label_width" :rules="data.form_rules">
                <template v-for="(item,key,index) in data.form_items">
                    <template v-if="data.form_rules[item.name] != ''">
                        <VaDyformItem :ref="'dyformitem_' + item.name" :key="index" :item="item" :form_values="data.form_values" :prop="item.name"></VaDyformItem>
                    </template>
                    <template v-else-if="data.form_rules[item.name] == ''">
                        <VaDyformItem :ref="'dyformitem_' + item.name" :key="index" :item="item" :form_values="data.form_values"></VaDyformItem>
                    </template>
                </template>
                <!-- 按钮 -->
                <div v-if="this.foot_hide == false">
                    <Divider />
                    <FormItem style="text-align:left">
                        <Button :loading="loading" type="primary" size="large" style="margin-right: 15px" @click="submit()">确认提交</Button>
                        <Button type="text" size="large" @click="cancel()">取消操作</Button>
                    </FormItem>
                </div>
            </Form>
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
    </div>
</template>
<script>
import VaDyformItem from '@/views/components/va_dypage/va_dyform_item'
export default {
    name: 'DynamicForm',
    components: {
        VaDyformItem
    },
    props: {
        api: '',
        foot_hide: false,
    },
    data() {
        return {
            loading: false,
            ref: 'form', //相当于子组件实例ID
            data: '',
            label_position: 'right',
            label_width: 100
        }
    },
    created() {
        this.ref = 'form_' + (new Date()).getTime()
    },
    beforeMount() {},
    mounted() {
        this.loadData()
    },
    beforeUpdate() {},
    updated() {},
    beforeDestroy() {},
    destroyed() {},
    computed: {},
    methods: {
        loadData(api = '') {
            if (api != '') {
                this.api = api
            }
            if (this.api) {
                let _this = this
                axios.get(this.api)
                    .then(function(res) {
                        res = res.data
                        if (res.code == '200') {
                            if (res.data.form_data.form_rules.length == 0) {
                                res.data.form_data.form_rules = new Object();
                            }
                            //console.log(res.data.form_data);
                            _this.data = res.data.form_data
                        } else {
                            _this.$Message.error(res.msg)
                        }
                    })
                    .catch(function(error) {
                        console.log(error)
                    });
            }
        },
        submit() {
            this.loading = true
            let _this = this
            // 获取checkboxtree的选中项目
            for (let index in _this.data.form_items) {
                if (_this.data.form_items[index].type == 'checkboxtree') {
                    let admin_auth = _this.$refs['dyformitem_' + _this.data.form_items[index].name][0].getChecked('admin_auth')
                    _this.data.form_values[_this.data.form_items[index].name] = admin_auth
                }
            };

            //提交数据
            if (this.data.form_rules.length == 0) {
                return this.submitForm()
            }
            this.$refs[_this.ref].validate((valid) => {
                if (valid) {
                    return _this.submitForm()
                }
            })
        },
        submitForm() {
            // 提交数据
            let _this = this
            switch (this.data.form_method) {
                case 'post':
                    return new Promise((resolve, reject) => {
                        axios.post(this.api, this.data.form_values)
                            .then(function(res) {
                                res = res.data
                                if (res.code == '200') {
                                    _this.$Message.success(res.msg)
                                    _this.$Modal.remove()
                                } else {
                                    _this.$Message.error(res.msg)
                                }
                                _this.loading = false
                                resolve(res)
                            })
                            .catch(function(error) {
                                _this.loading = false
                                reject(error)
                            });
                    })
                    break;
                case 'put':
                    axios.put(this.api, this.data.form_values)
                        .then(function(res) {
                            res = res.data
                            if (res.code == '200') {
                                _this.$Message.success(res.msg)
                                _this.$Modal.remove()
                                return true
                            } else {
                                _this.$Message.error(res.msg)
                            }
                            _this.loading = false
                        })
                        .catch(function(error) {
                            console.log(error)
                            _this.loading = false
                        });
                    break;

                default:
                    _this.$Message.error('form_method不存在')
                    _this.loading = false
                    break;
            }
        },
        cancel() {
            this.$refs[this.ref].resetFields()
        }
    },
    watch: {
        api() {
            this.loadData()
        }
    }
}
</script>