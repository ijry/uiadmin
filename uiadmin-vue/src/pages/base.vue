<style scoped lang="less">
</style>
<template>
    <div>
        <div class="bg-indigo-600">
            <div class="max-w-7xl mx-auto py-3 px-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between flex-wrap">
                <div class="w-0 flex-1 flex items-center">
                <span class="flex p-2 rounded-lg bg-indigo-800">
                    <SpeakerphoneIcon class="h-6 w-6 text-white" aria-hidden="true" />
                </span>
                <p class="ml-3 font-medium text-white truncate">
                    <span class="md:hidden"> We announced a new product! </span>
                    <span class="hidden md:inline"> 这是UiAdmin配套的前台Vue3（Vite+TypeScript构建）工程，可以以此为基石开发前台页面配合UiAdmin开发接口。</span>
                </p>
                </div>
                <div class="order-3 mt-2 flex-shrink-0 w-full sm:order-2 sm:mt-0 sm:w-auto">
                <a target="_blank" href="https://uiadmin.jiangruyi.com/docs/uiadmin" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50"> 了解更多 </a>
                </div>
                <div class="order-2 flex-shrink-0 sm:order-3 sm:ml-3">
                <button type="button" class="-mr-1 flex p-2 rounded-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-white sm:-mr-2">
                    <span class="sr-only">Dismiss</span>
                    <XIcon class="h-6 w-6 text-white" aria-hidden="true" />
                </button>
                </div>
            </div>
            </div>
        </div>
        <!-- 头部 -->
        <UiHeader></UiHeader>
        <router-view></router-view>
        <!-- 底部 -->
        <UiFooter></UiFooter>
    </div>
</template>
<script lang="ts" setup>
// This starter template is using Vue 3 <script setup> SFCs
// Check out https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup
import UiHeader from '@/components/UiHeader.vue'
import UiFooter from '@/components/UiFooter.vue'
import { SpeakerphoneIcon, XIcon } from '@heroicons/vue/outline'
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
