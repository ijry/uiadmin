<template>
  <div>
    <tc-block style="margin:5px;">
      <json-viewer
        :value="json"
        :expand-depth=5
        copyable
        boxed
        sort></json-viewer>
    </tc-block>
  </div>
</template>

<script>
import jsonViewer from 'vue-json-viewer'
export default {
  components: {jsonViewer},
  props: {json: Object, required: false, default: () => {}}
}
</script>

<style lang="scss" scoped>

</style>