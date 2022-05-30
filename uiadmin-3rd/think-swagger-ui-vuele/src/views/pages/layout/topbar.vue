<template>
  <div class="topbar-container">
    <el-menu class="topbar" mode="horizontal">
      <span class="logo-img" style="padding-top:5px;float:left;height:50px;padding-left:10px;">
        <img src="@/assets/logo.png" style="width:40px;height:40px;" />
      </span>
      <span class="title-font">
        <a :href="mainIndex">SwaggerUI</a>
      </span>
      <div class="avatar-container">
        <tc-button size="small" type="think" @click="openCommonSetting">通用设置</tc-button>
        <tc-button size="small" type="think" @click="openSwaggerInfo">swagger信息</tc-button>
      </div>
    </el-menu>
    <tc-dialog loading :title="swaggerInfoDialog.title" :visible.sync="swaggerInfoDialog.show" width="600px" height="400px">
      <swagger-info />
    </tc-dialog>
    <tc-dialog loading :title="commonSettingDialog.title" :visible.sync="commonSettingDialog.show" width="800px" height="450px">
      <common-setting />
    </tc-dialog>
  </div>
</template>

<script>
import swaggerInfo from './swaggerInfo'
import commonSetting from './commonSetting'
import { mapGetters } from 'vuex'
export default {
  components: { swaggerInfo, commonSetting },
  data() {
    return {
      swaggerInfoDialog: {
        show: false,
        title: 'swagger信息'
      },
      commonSettingDialog: {
        show: false,
        title: '通用设置'
      }
    }
  },
  computed: {
    ...mapGetters(['theme']),
    mainIndex: function() {
      return this.theme === 'admin' ? '/#/main/index' : '/#/simpleMain/index'
    }
  },
  created() {
  },
  methods: {
    openSwaggerInfo() {
      this.swaggerInfoDialog.show = true
    },
    openCommonSetting() {
      this.commonSettingDialog.show = true
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
$topbar-height: 50px;
$title-color:#29292a;
.topbar-container {
  height: $topbar-height;
}
.topbar {
  height: $topbar-height;
  line-height: $topbar-height;
  border-radius: 0px !important;
  background-color: #fff;
  .title-font {
    color: $title-color;
    font-size: 18px;
    line-height: $topbar-height;
    padding-left: 10px;
    font-weight: 800;
    float: left;
    a{
      text-decoration: none;
      color:$title-color;
    }
  }
  .avatar-container {
    height: $topbar-height;
    display: inline-block;
    position: absolute;
    right: 15px;
    .avatar-wrapper {
      cursor: pointer;
      margin-top: 5px;
      position: relative;
    }
  }
}
</style>
