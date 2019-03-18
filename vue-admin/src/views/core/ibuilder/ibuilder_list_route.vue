<template>
  <div>
    <IbuilderList :api="api"></IbuilderList>
  </div>
</template>

<script>
import IbuilderList from '@/views/core/ibuilder/ibuilder_list'
export default {
    name: 'IbuilderListRoute',
    components: {
        IbuilderList
    },
    data () {
        return {
            api: ''
        };
    },
    created: function(){
    },
    beforeMount: function(){
        this.loadData()
    },
    methods: {
        loadData () {
            if (this.$route.meta.dytype == 'dylist') {
                let _this = this
                let length = this.$route.params.length
                if (length > 0) {
                    this.$route.params.forEach(function(e){
                        _this.$route.meta.api = _this.$route.meta.api + '/' + _this.$route.params[e]
                    });
                    _this.api = _this.$route.meta.api
                } else {
                    _this.api = _this.$route.meta.api
                }
            }
        }
    },
    watch: {
        $route(){
            //this.loadData()
        },
    }
}
</script>

<style>
</style>
