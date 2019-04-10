<template>
    <div id="app">
        <router-view></router-view>
    </div>
</template>
<script>
export default {
    name: 'app'
}
</script>
<style lang="less">
@import './plugins/iview-variables.less';

#app {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    margin: 0 auto;
    height: 100%;
    color: #2c3e50;
}

.wrap {
    .container {
        width: 1100px;
        margin: 0 auto;
    }
    .head {}

    .body {
        .main-slider {
            height: 400px;
            .item-box {
                text-align: center;
                background: @primary-color;
                .slider-particles {
                    height: 400px;
                }
                .content {
                    width: 500px;
                    position: absolute;
                    left:0;right:0;
                    top:0;bottom:0;
                    margin:auto;
                    color: #fff;
                    padding: 50px;
                    .links span {
                        margin-right: 10px;
                    }
                }
            }
        }
        .main-banner {
            padding: 40px 0;
            margin-bottom: 40px;
            text-align: center;
            background: @primary-color;
            color: #fff;
            .title {
                color: #fff;
                font-size: 32px;
            }
            .info {
                margin-top: 12px;
                font-size: 16px;
            }
        }
    }
    .foot {}
}
</style>
