/**
 * +----------------------------------------------------------------------
 * | tpvue-admin [ 轻量级模块化Vue后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2019 http://tpvue.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/
<style scoped lang="less">
    .warp {
        .main-slider {
            height: 400px;
            .item-box {
                text-align: center;
                background: #0099CC;
                .slider-particles {
                    height: 400px;
                }
                .content {
                    width: 500px;
                    position: absolute;
                    left:0;right:0;
                    top:0;bottom:0;
                    margin:auto;
                    color: #fff;
                    padding: 50px;
                    .links span {
                        margin-right: 10px;
                    }
                }
            }
        }
    }
</style>
<template>
    <div class="warp">
        <IaHead></IaHead>
        <div class="body">
            <div class="container" style="margin-top: 30px;">
                <h1>
                    您的支持是我们开发的动力，支持我们有很多种方式：
                </h1>
                <h1>
                    1、觉得产品好用的可以帮忙推广宣传让更多的人知道。
                </h1>
                <h1>
                    2、为我们的产品在Github提交pull request
                </h1>
                <h1>
                    3、土豪直接捐赠
                </h1>
                <Row type="flex" justify="center" align="middle" >
                    <Col span="8">

                    </Col>
                    <Col span="8">
                        <img src="@/assets/alipay.jpg" style="width: 300px;">
                        <img src="@/assets/wechatpay.jpg" style="width: 300px;">
                    </Col>
                    <Col span="8">
                    </Col>
                </Row>
            </div>
        </div>
        <IaFoot></IaFoot>
    </div>
</template>
<script>
import IaHead from '@/views/components/ia_head'
import IaFoot from '@/views/components/ia_foot'
export default {
    components: {
        IaHead,
        IaFoot
    },
    created: function () {
    },
    methods: {
    }
}
</script>
