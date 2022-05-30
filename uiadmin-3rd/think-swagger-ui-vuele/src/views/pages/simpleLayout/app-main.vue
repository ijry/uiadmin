<template>
  <section class="app-main">
    <transition name="fade-transform" mode="out-in">
      <div>
        <router-view/>
      </div>
    </transition>
  </section>
</template>

<script>
export default {
  name: 'appMain',
  computed: {
  }
}
</script>

<style scoped>
.app-main {
  height: calc(100vh - 80px);
  position: relative;
  overflow: auto;
}
.app-main>div{
  padding: 5px !important;
}
</style>
