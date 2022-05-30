<template>
  <div>
    <h1>index1</h1>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  data() {
    return {
    }
  },
  mounted() {
  },
  watch: {
  },
  computed: {
    ...mapGetters([
      'menus'
    ])
  },
  methods: {
  }

}
</script>

<style lang="scss" scoped>

</style>