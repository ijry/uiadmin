<script setup>
import { ref } from 'vue'

defineProps({
  msg: String
})

const count = ref(0)
</script>

<template>
  <h1>{{ msg }}</h1>

  <p>
    
    <a href="https://gitee.com/uiadmin/uiadmin" target="_blank">Gitee</a>
    +
    <a href="https://github.com/ijry/uiadmin" target="_blank">Github</a>
  </p>

  <p>
    <a href="https://uiadmin.net/" target="_blank">UiAdmin Documentation</a>
  </p>

  <button type="button" @click="count++">count is: {{ count }}</button>
  <p>
    Edit
    <code>components/HelloWorld.vue</code> to test hot module replacement.
  </p>
</template>

<style scoped>
a {
  color: #42b983;
}
</style>
