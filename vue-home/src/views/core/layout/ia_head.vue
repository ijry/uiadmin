<style scoped lang="less">
    .main-nav {
        .layout {
            display: flex;
            justify-content: space-between;
            .layout-logo {
                display: flex;
                .logo {
                    margin-top: 8px;
                    margin-right: 8px;
                    height: 45px;
                }
                .title {
                    width: 85px;
                    height: 50px;
                    font-size: 18px;
                    font-weight: 500;
                    color: #0099CC;
                }
                .slogn {
                    width: 120px;
                    height: 50px;
                }
            }
            .layout-nav {
                margin-right: 20px;
            }
        }
    }
</style>
<template>
    <Menu class="main-nav" mode="horizontal" :theme="theme" :active-name="active">
        <div v-bind:class="{'container': hasContainer}">
            <div class="layout">
                <div class="layout-logo">
                    <div>
                        <img class="logo" src="@/assets/logo.png"/>
                    </div>
                    <div class="title">
                        <span>InitAdmin</span>
                    </div>
                    <div class="slogn">
                    </div>
                </div>
                <div class="layout-nav">
                    <MenuItem name="1" to="/" @click="this.active = 1">
                        首页
                    </MenuItem>
                    <MenuItem to="/preview" name="2" @click="this.active = 2">
                        在线演示
                    </MenuItem>
                    <MenuItem to="/downloads" name="5" @click="this.active = 5">
                        免费下载
                    </MenuItem>
                    <MenuItem to="/docs" name="7" @click="this.active = 7">
                        开发文档
                    </MenuItem>
                    <MenuItem to="/support" name="9"  @click="this.active = 9">
                        支持
                    </MenuItem>
                    <MenuItem name="11">
                        <Button type="primary" to="/user/login"style="margin-right: 8px;">登录</Button>
                        <Button type="success" to="/user/register">注册</Button>
                    </MenuItem>
                </div>
            </div>
        </div>
    </Menu>
</template>
<script>
    export default {
        name: 'ia_head',
        props: {
            hasContainerProp: {
                default: true
            }
        },
        data () {
            return {
                hasContainer: true,
                theme: 'light',
                active: 1
            };
        },
        created: function () {
            this.hasContainer = this.hasContainerProp
        },
        mounted:function(){
        },
        computed: {
        },
        methods: {
        },
        watch:{
        }
    }
</script>
