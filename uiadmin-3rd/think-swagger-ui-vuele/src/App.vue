<template>
  <div id="app" :class="appClassName">
    <router-view/>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'App',
  computed: {
    ...mapGetters(['theme']),
    appClassName: function() {
      if (this.theme === 'admin') {
        return 'main'
      } else if (this.theme === 'simple') {
        return 'simple'
      }
    }
  }
}
</script>

<style>
body{
  padding: 0px !important;
  margin: 0px !important;
}
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
</style>
