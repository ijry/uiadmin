<template>
  <div>
    <div style="padding:5px;">
      <tc-block title="header设置">
        <tc-button type="think" size="small" @click="addHeader">添加</tc-button>
        <div style="margin-top:10px;">
          <el-row :gutter="20">
            <el-col :span="2">
              <span style="margin-left:5px;">启用</span>
            </el-col>
            <el-col :span="7">
              <span>头名称</span>
            </el-col>
            <el-col :span="12">
              <span>头信息</span>
            </el-col>
            <el-col :span="3">
              <span>操作</span>
            </el-col>
          </el-row>
          <el-row v-for="(item, index) in headerList" :key="item.tid" :gutter="20" style="margin-top:5px;">
            <el-col :span="2" style="text-align:center;">
              <tc-checkbox v-model="item.use" style="margin-top:5px;" />
            </el-col>
            <el-col :span="7">
              <tc-input v-model="item.headerName" size="small" />
            </el-col>
            <el-col :span="12">
              <tc-input v-model="item.headerInfo" size="small" />
            </el-col>
            <el-col :span="3">
              <tc-button type="think" size="small" @click="deleteRow(index)">删除</tc-button>
            </el-col>
          </el-row>
        </div>
      </tc-block>
    </div>
    <tc-fixed-bottom style="text-align:center;line-height:30px;">
      <tc-button type="think" size="small" @click="saveData">保存</tc-button>
      <tc-button type="think" size="small" @click="$parent.hide">关闭</tc-button>
    </tc-fixed-bottom>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import store from '@/store'
export default {
  data() {
    return {
      headerItem: {use: true, headerName: null, headerInfo: null},
      headerList: []
    }
  },
  computed: {
    ...mapGetters([
      'headers'
    ])
  },
  methods: {
    opened() {
      this.headerList = [].concat(this.headers || [])
    },
    addHeader() {
      this.headerList.push(Object.assign({tid: Math.random()}, this.headerItem))
    },
    deleteRow(index) {
      this.headerList.splice(index, 1)
    },
    saveData() {
      store.commit('headers', this.headerList)
      this.$parent.hide()
    }
  }
}
</script>

<style lang="scss" scoped>

</style>