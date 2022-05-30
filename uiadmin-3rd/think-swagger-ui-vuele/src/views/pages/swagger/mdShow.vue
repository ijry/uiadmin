<template>
  <div>
    <Markdown v-model="content" />
  </div>
</template>

<script>
import Markdown from 'vue-meditor'
export default {
  components: {Markdown},
  props: {
    mdContent: {type: String, required: false, default: ''}
  },
  data() {
    return {
      content: null
    }
  },
  created() {
    this.content = this.mdContent
  },
  watch: {
    'mdContent': function(newVal) {
      this.content = newVal
    }
  }
}
</script>

<style lang="scss" scoped>

</style>