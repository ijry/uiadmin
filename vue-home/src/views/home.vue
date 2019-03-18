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
            <Carousel :autoplay="false" :loop="false" class="main-slider">
                <CarouselItem>
                    <div class="item-box">
                        <vue-particles
                            color="#fff"
                            :particleOpacity="0.7"
                            :particlesNumber="60"
                            shapeType="circle"
                            :particleSize="4"
                            linesColor="#fff"
                            :linesWidth="1"
                            :lineLinked="true"
                            :lineOpacity="0.4"
                            :linesDistance="150"
                            :moveSpeed="1"
                            :hoverEffect="true"
                            hoverMode="grab"
                            :clickEffect="true"
                            clickMode="push"
                            class="slider-particles"
                        >
                        </vue-particles>
                        <div class="content">
                            <h1 style="font-size: 30px;">InitAdmin</h1>
                            <p style="margin-top: 10px;font-size: 25px;">领先的渐进式模块化开源后台</p>
                            <p style="margin-top: 120px;font-size: 18px;">
                                <Button target="_blank" to="https://github.com/initadmin" type="default" ghost size="large" icon="ios-download-outline">立即下载 v2019.3</Button>
                            </p>
                            <p class="links" style="margin-top: 15px;">
                                <span>当前版本: v2019.3</span>
                                <span><Button type="text" ghost to="/updateLog">更新日志</Button></span>
                                <span><Button type="text" ghost target="_blank" to="https://github.com/initadmin">GitHub</Button></span>
                            </p>
                        </div>
                    </div>
                </CarouselItem>
            </Carousel>
            <div class="container">
                <Row type="flex" justify="center" align="middle" style="padding: 50px">
                    <Col span="24" style="text-align: center;font-size: 20px;margin-bottom: 80px;">
                        模块化技术
                    </Col>
                    <Col span="14">
                        <p style="width: 250px;font-size: 16px;">
                            InitAdmi后台本着高内聚低耦合的原则，
                            模块作为InitAdin的最小功能包可以共享
                            用户可以在模块市场上传下载模块
                        </p>
                    </Col>
                    <Col span="10">
                        <img style="height: 200px;" src="@/assets/logo.png"/>
                    </Col>
                </Row>
            </div>
            <div style="background: #eee;">
                <div class="container">
                    <Row type="flex" justify="center" align="middle" style="padding: 50px">
                        <Col span="24" style="text-align: center;font-size: 20px;margin-bottom: 80px;">
                            多平台支持
                        </Col>
                        <Col span="10">
                            <img style="height: 200px;" src="@/assets/logo.png"/>
                        </Col>
                        <Col span="14">
                            <p style="width: 450px;font-size: 16px;">
                                InitAdmin诞生在移动互联网后半场，面多各种
                                流量入口，InitAdmin将从如下方面对多个平台支持：
                                pc端采用web方式实现，手机端将采用uni-app技术，
                                达到一次开发全面覆盖iOS、安卓、微信小程序、支
                                付宝小程序、百度小程序、头条小程序、H5，从而
                                节省开发者的大量精力。
                            </p>
                        </Col>
                    </Row>
                </div>
            </div>
            <div class="container">
                <Row type="flex" justify="center" align="middle" style="padding: 50px">
                    <Col span="24" style="text-align: center;font-size: 20px;margin-bottom: 80px;">
                        多语言API兼容
                    </Col>
                    <Col span="14">
                        <p style="width: 400px;font-size: 16px;">
                            InitAdmin后台将打造统一的后台框架体系，
                            后端横跨php、java、python、node、.net
                            等等语言，前端将支持vue、react、angular
                            语言，多个语言支持通过统一的API标准兼容，
                            同时InitAdmin将从tp5+vue版本做起，先建立
                            API标准及示例，后期吸收优秀的志愿者加入
                            不同语言是实现开发。
                        </p>
                    </Col>
                    <Col span="10">
                        <img style="height: 200px;" src="@/assets/logo.png"/>
                    </Col>
                </Row>
            </div>
            <div style="background: #eee;">
                <div class="container">
                    <Row type="flex" justify="center" align="middle" style="padding: 50px">
                        <Col span="24" style="text-align: center;font-size: 20px;margin-bottom: 80px;">
                            IA动态页面技术
                        </Col>
                        <Col span="10">
                            <img style="height: 200px;" src="@/assets/logo.png"/>
                        </Col>
                        <Col span="14">
                            <p style="width: 400px;font-size: 16px;">
                                InitAdmin首创自主研发了基于前后端分离的
                                页面自动生成技术，目前支持ia_dylist和
                                ia_dyform，前者自动生成列表后者自动
                                生成表单，二者结合可以完成90%以上的
                                后台功能需求。
                            </p>
                        </Col>
                    </Row>
                </div>
            </div>
            <div class="container">
                <Row type="flex" justify="center" align="middle" style="padding: 50px">
                    <Col span="8" style="text-align: center;">
                        <div>
                            <img style="height: 80px;" src="@/assets/logo.png" />
                        </div>
                        <p style="font-size: 16px;margin-top: 15px;width: 300px;">
                            InitAdmin帮助我们节省了大量开发成本，项目顺利上线！
                        </p>
                    </Col>
                    <Col span="8" style="text-align: center;">
                        <div>
                            <img style="height: 80px;" src="@/assets/logo.png" />
                        </div>
                        <p style="font-size: 16px;margin-top: 15px;width: 300px;">
                            InitAdmin的API接口文档后台填写，自动生成前端开发用的文档功能真的解放了生产力很好用。
                        </p>
                    </Col>
                    <Col span="8" style="text-align: center;">
                        <div>
                            <img style="height: 80px;" src="@/assets/logo.png" />
                        </div>
                        <p style="font-size: 16px;margin-top: 15px;width: 300px;">
                            InitAdmin动态页面技术很好用，只要一个人就能完成后台所有工作！
                        </p>
                    </Col>
                </Row>
            </div>
        </div>
        <IaFoot></IaFoot>
    </div>
</template>
<script>
import IaHead from '@/views/core/layout/ia_head'
import IaFoot from '@/views/core/layout/ia_foot'
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
