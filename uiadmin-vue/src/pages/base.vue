<style scoped lang="less">
</style>
<template>
    <div>
        <!-- 头部 -->
        <router-view></router-view>
        <!-- 底部 -->
    </div>
</template>
<script lang="ts" setup>
// This starter template is using Vue 3 <script setup> SFCs
// Check out https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup
// import UiHeader from '@/components/UiHeader.vue'
// import UiFooter from '@/components/UiHeader.vue'
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
