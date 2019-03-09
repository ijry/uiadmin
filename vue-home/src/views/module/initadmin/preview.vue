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
            <div class="container" style="margin-top: 15px;">
                <img src="@/assets/preview/0.png" style="width: 100%;">
                <img src="@/assets/preview/1.png" style="width: 100%;">
                <img src="@/assets/preview/2.png" style="width: 100%;">
                <img src="@/assets/preview/3.png" style="width: 100%;">
                <img src="@/assets/preview/4.png" style="width: 100%;">
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
