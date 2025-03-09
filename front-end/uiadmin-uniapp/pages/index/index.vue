
          
  <template>
    <view
        class="fv-page flex flex-col px-4"
        style=""
    >
        <up-swiper
            class="flex flex-row mb-0"
            style=""
            :list='[{"url":"https://s3.bmp.ovh/imgs/2024/11/22/05da992b52bda808.png","title":"昨夜星辰昨夜风，画楼西畔桂堂东"},{"url":"https://s3.bmp.ovh/imgs/2024/11/22/05da992b52bda808.png","title":"身无彩凤双飞翼，心有灵犀一点通"},{"url":"https://s3.bmp.ovh/imgs/2024/11/22/05da992b52bda808.png","title":"谁念西风独自凉，萧萧黄叶闭疏窗，沉思往事立残阳"}]'
            imgMode="heightFix"
            height="500rpx"
            interval="5000"
            keyName="url"
            :autoplay='true'
            :circular='true'
        >
        </up-swiper>
        <view
            class="flex box-border mb-3"
            style=""
            :style='{"url":"","position":"static","flexDirection":"column","justifyContent":"center","alignItems":"center","flex1":"auto","width":"100%","height":"100%","padding":"10px 10px 10px 10px","margin":"0px 0px 0px 0px","color":"#333333","background":"#fffffffc","borderRadius":"0px","borderColor":"#ffffff","borderWidth":"0px","borderStyle":"solid","top":"","bottom":"","left":"","right":""}'
        >
            <up-text
                class=""
                style=""
                color="#303133"
                size="15px"
                text="这是FastView可视化导出的代码请将新的代码覆盖即可查看效果"
                align="left"
                wordWrap="normal"
                :show='true'
                iconStyle="15px"
                decoration="none"
            >
            </up-text>
            <view
                class="flex box-border"
                style=""
                :style='{"display": flex,"url":"","position":"static","flexDirection":"row","justifyContent":"flex-start","alignItems":"flex-start","flex1":"auto","width":"100%","height":"100%","padding":"10px 0 0 0","margin":"0px 0px 0px 0px","color":"#333333","background":"#ffffff00","borderRadius":"0px","borderColor":"#ffffff","borderWidth":"0px","borderStyle":"solid","top":"","bottom":"","left":"","right":""}'
            >
                <view
                    class="flex box-border"
                    style=""
                    :style='{"url":"","position":"static","flexDirection":"row","justifyContent":"flex-start","alignItems":"flex-start","flex1":"auto","width":"100%","height":"100%","padding":"0","margin":"0px 0px 0px 0px","color":"#333333","background":"#ffffffNaN","borderRadius":"0px","borderColor":"#ffffff","borderWidth":"0px","borderStyle":"solid","top":"","bottom":"","left":"","right":""}'
                >
                    <up-tag
                        class="mr-2"
                        style=""
                        text="百亿补贴"
                        type="error"
                        size="mini"
                        shape="square"
                        :plain='true'
                        :show='true'
                    >
                    </up-tag>
                    <up-tag
                        class=""
                        style=""
                        text="正品发票"
                        type="success"
                        size="mini"
                        shape="square"
                        :show='true'
                    >
                    </up-tag>
                </view>
                <view
                    class="flex box-border"
                    style=""
                    :style='{"url":"","position":"static","flexDirection":"column","justifyContent":"center","alignItems":"center","flex1":"auto","width":"100px","height":"100%","padding":"0","margin":"0px 0px 0px 0px","color":"#333333","background":"#ffffffNaN","borderRadius":"0px","borderColor":"#ffffff","borderWidth":"0px","borderStyle":"solid","top":"","bottom":"","left":"","right":""}'
                >
                    <up-button
                        class=" mb-3"
                        style=""
                        text="购买"
                        type="error"
                        shape="square"
                        size="mini"
                    >
                    </up-button>
                </view>
            </view>
            <up-line
                class="!mb-3"
                style=""
                color="#d6d7d9"
                length="100%"
                direction="row"
                :hairline='true'
            >
            </up-line>
            <view
                class="flex box-border"
                style=""
                :style='{"url":"","position":"static","flexDirection":"row","justifyContent":"center","alignItems":"center","flex1":"auto","width":"100%","height":"100%","padding":"0px 0","margin":"0px 0px 0px 0px","color":"#333333","background":"#ffffffNaN","borderRadius":"0px","borderColor":"#ffffff","borderWidth":"0px","borderStyle":"solid","top":"","bottom":"","left":"","right":""}'
            >
                <up-text
                    class=""
                    style=""
                    color="#909090FF"
                    size="12px"
                    text="退货包运费 7天无理由退货"
                    align="left"
                    wordWrap="normal"
                    :show='true'
                    iconStyle="15px"
                    decoration="none"
                >
                </up-text>
                <up-text
                    class=""
                    style=""
                    color="#909090FF"
                    size="12px"
                    text="已售1000+"
                    align="right"
                    wordWrap="normal"
                    :show='true'
                    iconStyle="15px"
                    decoration="none"
                >
                </up-text>
            </view>
            <view
                class="flex box-border"
                style=""
                :style='{"url":"","position":"static","flexDirection":"column","justifyContent":"space-between","alignItems":"flex-start","flex1":"auto","width":"100%","height":"100%","padding":"10px 0","margin":"0px 0px 0px 0px","color":"#333333","background":"#ffffffNaN","borderRadius":"0px","borderColor":"#ffffff","borderWidth":"0px","borderStyle":"solid","top":"","bottom":"","left":"","right":""}'
            >
                <up-tag
                    class=""
                    style=""
                    text="可享三期免息，约1022.1元/月"
                    type="primary"
                    size="mini"
                    shape="circle"
                    bgColor="#E6FAF2FF"
                    color="#03B17AFF"
                    borderColor="#FF000000"
                    :show='true'
                >
                </up-tag>
            </view>
        </view>
        <view
            class="flex box-border mb-3"
            style=""
            :style='{"url":"","position":"static","flexDirection":"column","justifyContent":"center","alignItems":"center","flex1":"auto","width":"100%","height":"100%","padding":"10px 10px 10px 10px","margin":"10px 0px 0px 0px","color":"#333333","background":"#ffffffff","borderRadius":"0px","borderColor":"#FF0404FF","borderWidth":"0px","borderStyle":"solid","top":"","bottom":"","left":"","right":""}'
        >
            <up-tabs
                class=""
                style=""
                :list='[{"name":"详情"},{"name":"评价"},{"name":"问答"}]'
                keyName="name"
                lineColor="#DE1705FF"
                lineWidth="20"
                lineHeight="3"
                lineBgSize="cover"
                :scrollable='true'
                duration="300"
            >
            </up-tabs>
            <view
                class="flex box-border"
                style=""
                :style='{"url":"","position":"static","flexDirection":"column","justifyContent":"center","alignItems":"center","flex1":"auto","width":"100%","height":"100%","padding":"10px 10px 10px 10px","margin":"0px 0px 0px 0px","color":"#333333","background":"#ffffffNaN","borderRadius":"0px","borderColor":"#ffffff","borderWidth":"0px","borderStyle":"solid","top":"","bottom":"","left":"","right":""}'
            >
                <up-image
                    class="flex flex-row"
                    style=""
                    src="https://img.alicdn.com/imgextra/i4/3253567997/O1CN018WdMg328wgB1GUHt4_!!3253567997.jpg"
                    mode="widthFix"
                    width="100%"
                    height="100%"
                    shape="square"
                    radius="0px"
                    :lazyLoad='true'
                    duration="500"
                    bgColor="#f3f4f6NaN"
                    :showMenuByLongpress='true'
                >
                </up-image>
            </view>
        </view>
        <view
            class="flex box-border mb-3"
            style=""
            :style='{"url":"","position":"static","flexDirection":"row","justifyContent":"center","alignItems":"center","flex1":"auto","width":"100%","height":"100%","padding":"10px 10px 10px 10px","margin":"0px 0px 0px 0px","color":"#333333","background":"#ffffffNaN","borderRadius":"0px","borderColor":"#ffffff","borderWidth":"0px","borderStyle":"solid","top":"","bottom":"","left":"","right":""}'
        >
            <view
                class="flex box-border"
                style=""
                :style='{"url":"","position":"static","flexDirection":"column","justifyContent":"center","alignItems":"center","flex1":"auto","width":"70%","height":"100%","padding":"10px 10px 10px 10px","margin":"0px 0px 0px 0px","color":"#333333","background":"#ffffff","borderRadius":"0px","borderColor":"#ffffff","borderWidth":"0px","borderStyle":"solid","top":"","bottom":"","left":"","right":""}'
            >
            </view>
            <view
                class="flex box-border"
                style=""
                :style='{"url":"","position":"static","flexDirection":"column","justifyContent":"center","alignItems":"center","flex1":"auto","width":"100%","height":"100%","padding":"10px 10px 10px 10px","margin":"0px 0px 0px 0px","color":"#333333","background":"#ffffff","borderRadius":"0px","borderColor":"#ffffff","borderWidth":"0px","borderStyle":"solid","top":"","bottom":"","left":"","right":""}'
            >
            </view>
        </view>
    </view>
  </template>
  <script setup>

  </script>
