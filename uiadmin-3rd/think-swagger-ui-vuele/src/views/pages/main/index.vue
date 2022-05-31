<template>
  <div class="jumbotron text-center">
      <div class="container">
          <div class="nav">
          </div>
          <el-row type="" align="top">
              <el-col :xs="24":sm="24" :md="14" :lg="14" class="left">
                  <h1>
                      UiAdmin渐进式通用后台
                      <span class="version"></span>
                  </h1>
                  <p class="description">
                      UiAdmin是一套渐进式模块化开源后台，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能，模块市场；同时我们将打造一套兼容性的API标准，从ThinkPHP6+Vue2开始，逐步覆盖spring-boot、nodejs等多语言框架。
                  </p>
                  <div class="flex" style="justify-content: flex-start;margin-top: 3em;flex-wrap: wrap;">
                      <div class="buttons">
                          <el-button
                              type="default" plain @click="switchType('https://uiadmin.net')">
                              官网
                          </el-button>
                          <el-button
                              type="default" plain @click="switchType('https://uiadmin.net/uiadmin')">
                              开发文档
                          </el-button>
                          <el-button
                              type="default" plain @click="switchType('/apidoc/')">
                              接口文档
                          </el-button>
                          <el-button
                              type="default" plain  @click="switchType('/admin/')">
                              打开后台
                          </el-button>
                          <div style="text-align: center;font-size: 12px;color: rgba(255,255,255,0.3);">
                          </div>
                      </div>
                      <div class="qr">
                      </div>
                  </div>
              </el-col>
              <el-col :xs="24" :sm="24" :md="10" :lg="10" class="right">
                  <div class="image-box">
                      <img class="main-img" src="https://cdn.jiangruyi.com/sbase/image/uniadmin/wap.png">
                      <img class="iphone" src="https://cdn.jiangruyi.com/sbase/image/iphone.png">
                  </div>
              </el-col>
          </el-row>
          <div class="bottom">
              <span>Copyright © 2018-2022 UiAdmin All Rights Reserved</span>
              <span>苏ICP备18067203号</span>
          </div>
      </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  data() {
    return {
    }
  },
  mounted() {
  },
  watch: {
  },
  computed: {
    ...mapGetters([
      'menus'
    ])
  },
  methods: {
  }

}
</script>

<style>
    body {
        padding: 0;
        margin: 0;
        background: #3e3f4d;
        height: 100vh;
    }
    .container {
        height: 100vh;
        min-height: 100vh;
        max-width: 1200px;
        margin: 0 auto;
    }
    .jumbotron{
        height: 100vh;
        box-sizing: border-box;
        color: #fff;
        margin-bottom: 0;
        background-image: url(https://cdn.jiangruyi.com/sbase/image/bg-1.png);
        background-size: 100% 100%;
    }
    .left {
        padding-top: 16em;
        padding-left: 20px;
        text-align: left;
    }
    .description {
        padding: 20px 0;
        line-height: 25px;
    }
    .version {
        font-size: 14px;
    }
    .image-box {
        width: 21em;
        margin: 0 auto;
        position: relative;
    }
    .right {
        box-sizing: border-box;
        padding-top: 3em;
        height: 43em;
    }
    .iphone {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        width: 21em;
        height: 43em;
    }
    .main-img {
        position: absolute;
        top: 18px;
        left: 20px;
        right: 0;
        width: 19em;
        height: 40.5em;
        border-radius: 20px;
    }
    .main-iframe {
        position: absolute;
        top: 0.9em;
        left: 20px;
        right: 0;
        width: 18.5em;
        height: 41em;
        border-radius: 40px;
        z-index: 999;
        border: none;
    }
    .main-iframe::-webkit-scrollbar {
        display: none;
    }
    .bottom {
        text-align: center;
        font-size: 13px;
        margin-top: 10em;
        padding-bottom: 5px;
        color: #999;
    }
    .bottom span {
        margin-right: 18px;
    }
    .nav {
        display: flex;
        justify-content: space-between;
    }
    .flex {
        display: flex;
        justify-content: space-between;
    }
    .xyicon {
        font-size: 20px;
    }
    .buttons {
        width: 350px;
    }
    .buttons .el-button {
        color: #eee;
        width: 140px;
        margin-bottom:20px;
        margin-left: 0px !important;
        margin-right: 20px !important;
        background: transparent !important;
    }
    .buttons .el-button.is-disabled {
        border-color: #C0C4CC !important;
    }
    .qr {
        text-align: center;
    }
    @media screen and (max-width: 1000px) {
        .container {
            width: 100%;
            box-sizing: border-box;
            margin: 0 auto;
        }
        .left {
            padding-top: 5%;
            text-align: center;
        }
        .right {
            height: 51em;
            padding-top: 80px;
        }
        .buttons {
            width: 100%;
        }
        .qr {
            width: 100%;
            margin-top: 30px;
            text-align: center;
        }
    }
  </style>