<template>
  <div>
    <div class="top-main">
      <topbar />
      <left-menu class="sidebar-container" />
      <div class="main-container">
        <app-main />
      </div>
    </div>
  </div>
</template>

<script>
import topbar from '../layout/topbar'
import appMain from './app-main'
import leftMenu from '../layout/sidebar'
export default {
  components: {topbar, appMain, leftMenu}
}
</script>

<style lang="scss" scoped>
.top-main{
  width: 1024px;
  margin: 0 auto;

  .sidebar-container{
    left: auto !important;
  }

  .el-submenu__title{
    background-color: #fff !important;
    color: #333 !important;
  }
}

</style>