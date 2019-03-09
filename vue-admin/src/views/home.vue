<style scoped lang="less">
@media screen and (min-width: 768px) {
    .index-card {
        height: 350px;
        &:nth-child(odd) {
            margin-right: 8px;
        }
        &:nth-child(even) {
            margin-left: 8px;
        }
    }
}
@media screen and (max-width: 768px) {
    .index-card {
        margin-bottom: 15px;
    }
}
</style>
<template>
    <div class="index">
        <Row type="flex" justify="start" align="top">
            <Col :xs="24" :sm="12">
                <Card class="index-card" :bordered="false" style="">
                    <p slot="title">系统信息</p>
                    <div>
                        <div>后台名称：InitAdmin后台</div>
                        <div>官方网站：<a target="_blank" href="https://initadmin.net">https://initadmin.net</a></div>
                        <div>开发文档：<a target="_blank" href="https://initadmin.net/#/docs">https://initadmin.net/#/docs</a></div>
                        <div>客户端框架：vue-admin v0.1.0</div>
                        <div>服务端框架：actionphp v0.1.0</div>
                        <div>
                            <span>Github：</span>
                            <a target="_blank" href="https://github.com/initadmin">https://github.com/initadmin</a>
                        </div>
                        <Divider/>
                        <div>PHP版本：7.0</div>
                        <div>数据库版本：Mysql5.7</div>
                        <div>文件上传限制：100M</div>
                    </div>
                </Card>
            </Col>
            <Col :xs="24" :sm="12">
                <Card class="index-card" :bordered="false">
                    <p slot="title">交流群</p>
                    <div>
                        <img style="width: 200px;margin-right: 10px;" src="@/assets/qun_qq.png">
                        <img style="width: 200px;" src="@/assets/qun_weixin.jpg">
                    </div>
                </Card>
            </Col>
        </Row>
    </div>
</template>
<script>
    export default {
        created: function () {
        },
        methods: {
        }
    }
</script>
