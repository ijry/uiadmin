<style scoped lang="less">
    .content{
        width: 100%;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        text-align: center;
        background: #000;
    }
</style>
<template>
    <div class="content">
        <Row type="flex" justify="center" align="middle" style="margin-top: 10%">
            <Col :xs="20" :sm="10" :md="6" :lg="5">
                <div style="width: 100%;color: #fff;margin-bottom: 30px;">
                    <img style="width: 120px;margin-bottom: 10px;" src="@/assets/logo.png" alt="initadmin">
                    <h2>InitAdmin后台管理系统</h2>
                </div>
                <Form ref="formLogin" :model="formLogin" :rules="ruleLogin">
                    <FormItem prop="account">
                        <Input size="large" type="text" v-model="formLogin.account" placeholder="账号">
                            <Icon type="ios-person-outline" slot="prepend"></Icon>
                        </Input>
                    </FormItem>
                    <FormItem prop="password">
                        <Input size="large" type="password" v-model="formLogin.password" placeholder="密码">
                            <Icon type="ios-lock-outline" slot="prepend"></Icon>
                        </Input>
                    </FormItem>
                    <FormItem>
                        <Button size="large" long :loading="loading" type="primary" @click="handleSubmit('formLogin')">
                            登录
                        </Button>
                    </FormItem>
                </Form>
            </Col>
        </Row>
    </div>
</template>
<script>
import util from '@/libs/util'
export default {
    data() {
        return {
            isDemo: false,
            loading: false,
            formLogin: {
                account: '',
                password: ''
            },
            ruleLogin: {
                account: [
                    { required: true, message: '请填写账号', trigger: 'blur' }
                ],
                password: [
                    { required: true, message: '请填写密码', trigger: 'blur' },
                    { type: 'string', min: 6, message: '密码至少6位', trigger: 'blur' }
                ]
            }
        }
    },
    created() {
        if (this.isDemo == true) {
            this.formLogin.account = 'initadmin'
            this.formLogin.password = 'initadmin.net'
        }
    },
    methods: {
        handleSubmit () {
            this.loading = true
            let _this = this
            axios.post('/v1/core/user/login', {
                    identity_type: 0,
                    identifier: _this.formLogin.account,
                    credential: _this.formLogin.password
                })
                .then(function (res) {
                    res = res.data
                    //console.log(res)
                    if (res.code == '200') {
                        _this.$store.commit('set_token', res.data.token)
                        if (util.getToken()) {
                            _this.$Message.success(res.msg)
                            _this.$router.push('/')
                        } else {
                            _this.$Message.error('本地token存储失败')
                        }
                    } else {
                        _this.$Message.error(res.msg)
                    }
                    _this.loading = false
                })
                .catch(function (error) {
                    _this.loading = false
                });
        }
    }
}
</script>
