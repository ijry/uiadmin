<style scoped lang="less">
</style>
<template>
  <main>
    <div class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
      <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
        <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
          <polygon points="50,0 100,0 50,100 0,100" />
        </svg>

        <Popover>
          <div class="relative pt-6 px-4 sm:px-6 lg:px-8">
            
          </div>

        </Popover>

        <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
          <div class="sm:text-center lg:text-left">
            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
              <span class="block xl:inline">UIADMIN</span>
              {{ ' ' }}
              <span class="block text-indigo-600 xl:inline">轻量级全栈通用后台</span>
            </h1>
            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
              UiAdmin是一套零前端代码通用后台，采用前后端分离技术，数据交互采用json格式；通过后端Builder不需要一行前端代码就能实时体验一个vue+element的现代化后台；同时我们打造一了套兼容性的API标准，从SpringBoot、ThinkPHP、Laravel、.NET开始，逐步覆盖js、go等多语言和框架。
            </p>
            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
              <div class="rounded-md shadow">
                <a target="_blank" href="https://uiadmin.jiangruyi.com/docs/uiadmin" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                  立即开始
                </a>
              </div>
              <div class="mt-3 sm:mt-0 sm:ml-3">
                <a target="_blank" href="https://gitee.com/uiadmin" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-10">
                  Gitee (码云)
                </a>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
      <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2850&q=80" alt="" />
    </div>
  </div>

    <div class="py-12 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <div class="lg:text-center">
          <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">A better way to code</h2>
          <p class="mt-6 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">我们的优势</p>
          <p class="mt-10 max-w-2xl text-xl text-gray-500 lg:mx-auto">
            整个后台通过基于Vue的先进的Builder页面自动生成技术解析json渲染而成
          </p>
        </div>

        <div class="mt-20">
          <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
            <div v-for="feature in features" :key="feature.name" class="relative">
              <dt>
                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                  <component :is="feature.icon" class="h-6 w-6" aria-hidden="true" />
                </div>
                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">{{ feature.name }}</p>
              </dt>
              <dd class="mt-2 ml-16 text-base text-gray-500">
                {{ feature.description }}
              </dd>
            </div>
          </dl>
        </div>
      </div>
    </div>

    <div class="bg-gray-50">
      <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
          <span class="block">准备好了吗?</span>
          <span class="block text-indigo-600 mt-1">立即免费开始.</span>
        </h2>
        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
          <div class="inline-flex rounded-md shadow">
            <a target="_blank" href="https://uiadmin.jiangruyi.com/docs/uiadmin" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
              立即开始
            </a>
          </div>
          <div class="ml-3 inline-flex rounded-md shadow">
            <a target="_blank" href="https://gitee.com/uiadmin" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
              Gitee
            </a>
          </div>
        </div>
      </div>
    </div>
    </main>
</template>
<script lang="ts" setup>
// This starter template is using Vue 3 <script setup> SFCs
// Check out https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup

import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { MenuIcon, XIcon } from '@heroicons/vue/outline'


import { AnnotationIcon, GlobeAltIcon, LightningBoltIcon, ScaleIcon } from '@heroicons/vue/outline'
const features = [
  {
    name: '扩展化',
    description:
      '支持扩展化开发，有利于功能代码的组织，扩展作为一个最小功能包，可以被创建、导出、分发、安装。',
    icon: GlobeAltIcon,
  },
  {
    name: 'Builder构建',
    description:
      '基于自主研发的第二代(vue+cloud)Builder页面构建技术可以非常快速高效的构建后台页面，支持list、form、info、tab、stack、excel等。',
    icon: ScaleIcon,
  },
  {
    name: '全API开发',
    description:
      '前后台全部基于API分离式开发，后台也可以基于API开发客户端，同时拥有双路由机制，也支持前后端不分离传统式开发。',
    icon: LightningBoltIcon,
  },
  {
    name: '多平台支持',
    description:
      'pc端采用vue等方式实现，手机端采用uni-app技术， 达到一次开发全面覆盖iOS、安卓、微信小程序、支 付宝小程序、百度小程序、头条小程序、H5等平台。',
    icon: AnnotationIcon,
  },
]

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
