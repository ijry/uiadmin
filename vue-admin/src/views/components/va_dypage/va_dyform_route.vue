<template>
  <div>
    <Card shadow>
        <VaDyform :api="api" :foot_hide="false"></VaDyform>
        <div style="text-align: right;font-size: 12px;color: #808695;transform: scale(0.8)">via iadypage</div>
    </Card>
  </div>
</template>

<script>
import VaDyform from '@/views/components/va_dypage/va_dyform'
export default {
    name: 'va_dyform_route',
    components: {
        VaDyform
    },
    data () {
        return {
            api: this.$route.meta.api
        };
    },
    created: function(){
    },
    beforeMount: function(){
        this.loadData()
    },
    methods: {
        loadData () {
            if (this.$route.meta.dytype == 'dyform') {
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
