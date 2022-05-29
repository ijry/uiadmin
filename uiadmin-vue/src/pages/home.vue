<style scoped lang="less">
</style>
<template>
    <div>
      <img alt="Vue logo" src="@/assets/logo.png" />
      <HelloWorld msg="Hello Vue 3 + Vite + uiadmin" />
      <h1 class="text-3xl font-bold underline text-red-400">
        Hello world!
      </h1>
    </div>
</template>
<script lang="ts" setup>
// This starter template is using Vue 3 <script setup> SFCs
// Check out https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup
import HelloWorld from '@/components/HelloWorld.vue'
</script>
<script lang="ts">
export default {
    data() {
        return {
          loading: false,
        }
    },
    created: function () {
    },
    watch: {
    },
    methods: {
        async loadData() {
        }
    }
}
</script>
