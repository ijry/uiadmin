<template>
  <div>
    <topbar />
    <sidebar class="sidebar-container" />
    <div class="main-container">
      <app-main />
    </div>
  </div>
</template>

<script>
import topbar from './topbar'
import sidebar from './sidebar'
import appMain from './app-main'
export default {
  components: {topbar, sidebar, appMain},
  computed: {
  },
  methods: {
  }
}
</script>

<style lang="scss" scoped>

</style>