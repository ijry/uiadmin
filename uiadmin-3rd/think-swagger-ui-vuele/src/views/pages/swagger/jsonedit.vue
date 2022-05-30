<template>
  <div>
    <div id="jsoneditor" style="width: 800px; height: 510px;"></div>
    <tc-fixed-bottom style="text-align:center;line-height:30px;">
      <tc-button type="think" size="small" @click="saveJson">保存</tc-button>
      <tc-button type="think" size="small" forceEnabled @click="$parent.hide">关闭</tc-button>
    </tc-fixed-bottom>
  </div>
</template>

<script>
import JSONEditor from 'jsoneditor'
import 'jsoneditor/dist/jsoneditor.min.css'
export default {
  props: {json: String, required: false, default: '{}'},
  data() {
    return {
      editor: null
    }
  },
  mounted() {
    const container = document.getElementById('jsoneditor')
    const options = {
      mode: 'text',
      sortObjectKeys: false,
      enableSort: false,
      enableTransform: false
    }
    this.editor = new JSONEditor(container, options)
  },
  methods: {
    opened() {
      this.initFormat()
    },
    initFormat() {
      this.editor.set(JSON.parse(this.json))

      // get json
      // const updatedJson = this.editor.get()
      // console.log(updatedJson)
    },
    saveJson() {
      this.$emit('save-json', this.editor.get())
    }
  }
}
</script>

<style lang="scss" scoped>

</style>