
<template>
  <component v-bind="linkProps(to,param)">
    <slot/>
  </component>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  props: {
    to: {type: String, required: true},
    param: {type: Object, required: false}
  },
  computed: {
    ...mapGetters(['theme'])
  },
  methods: {
    linkProps(to, param) {
      const rootPath = this.getRootPath()
      return {
        is: 'router-link',
        to: {path: rootPath, query: param }
      }
    },
    getRootPath() {
      if (this.theme === 'admin') {
        return '/swagger/index'
      } else if (this.theme === 'simple') {
        return '/simpleSwagger/index'
      }
    }
  }
}
</script>
