
  <template>
    <view
        class="fv-page flex flex-col px-4"
        style=""
    >
        <up-navbar
            class=""
            style=""
            :autoBack="true"
            bgColor="#FFFFFFFF"
            :fixed="true"
            :placeholder="true"
            title="我的"
            titleColor="#FFFFFFFF"
            leftIconColor="#FFFFFFFF"
        >
            <template v-slot:left>
            <view
                class="flex box-border"
                style=""
                :style="{'url':'','position':'static','flexDirection':'column','justifyContent':'center','alignItems':'center','flex1':'auto','width':'100%','height':'100%','padding':'10px 10px 10px 10px','margin':'0px 0px 0px 0px','color':'#333333','background':'#ffffff00','borderRadius':'0px','borderColor':'#ffffff','borderWidth':'0px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
            >
            </view>
            </template>
        </up-navbar>
        <view
            class="flex box-border"
            style=""
            :style="{'url':'','position':'static','flexDirection':'row','justifyContent':'center','alignItems':'center','flex1':'auto','width':'100%','height':'100%','padding':'0px 0px 0px 0px','margin':'0px 0px 10px 0px','color':'#333333','background':'#ffffffff','borderRadius':'0px','borderColor':'#ffffff','borderWidth':'0px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
        >
            <up-cell-group
                class="pb-2"
                style=""
            >
                <up-cell
                    class=""
                    style=""
                    title="昵称123"
                    label="描述信息"
                    :isLink="true"
                >
                    <template v-slot:icon>
                    <view
                        class="flex box-border"
                        style=""
                        :style="{'url':'','position':'static','flexDirection':'column','justifyContent':'center','alignItems':'center','flex1':'auto','width':'100%','height':'100%','padding':'10px 0','margin':'0px 15px 0px 0px','color':'#333333','background':'#ffffffNaN','borderRadius':'0px','borderColor':'#ffffff','borderWidth':'0px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
                    >
                        <up-image
                            class="flex flex-row"
                            style=""
                            src="https://img0.baidu.com/it/u=1825491255,3269601434&fm=253&fmt=auto&app=120&f=JPEG?w=889&h=500"
                            mode="aspectFill"
                            width="70px"
                            height="70px"
                            shape="square"
                            radius="20px"
                            :lazyLoad="true"
                            duration="500"
                            bgColor="#f3f4f6NaN"
                            :showMenuByLongpress="true"
                        >
                        </up-image>
                    </view>
                    </template>
                    <template v-slot:right-icon>
                    <up-icon
                        class=""
                        style=""
                        name="grid"
                        color="#797A7CFF"
                        size="18px"
                        labelPos="right"
                        labelSize="15px"
                        labelColor="#606266"
                        space="3px"
                        imgMode="aspectFill"
                        top="0"
                    >
                    </up-icon>
                    </template>
                    <template v-slot:title>
                    <up-text
                        class=""
                        style=""
                        color="#303133"
                        size="18px"
                        text="昵称123"
                        align="left"
                        wordWrap="normal"
                        :show="true"
                        iconStyle="15px"
                        decoration="none"
                    >
                    </up-text>
                    </template>
                </up-cell>
            </up-cell-group>
        </view>
        <view
            class="flex box-border w-full"
            style=""
            :style="{'url':'','position':'static','flexDirection':'row','justifyContent':'center','alignItems':'center','flex1':'auto','width':'100%','height':'150rpx','padding':'0','margin':'0px 0px 10px 0px','color':'#333333','background':'#fffffffc','borderRadius':'0px','borderColor':'#ffffff','borderWidth':'0px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
        >
            <up-cell-group
                class=""
                style=""
            >
                <up-cell
                    class=""
                    style=""
                    title="服务"
                    label="描述信息"
                    :isLink="true"
                >
                    <template v-slot:icon>
                    <up-icon
                        class="mr-2"
                        style=""
                        name="https://s3.bmp.ovh/imgs/2024/11/25/e5d598a4bad506e6.png"
                        color="#03CE10FF"
                        size="25px"
                        labelPos="right"
                        labelSize="15px"
                        labelColor="#606266"
                        space="3px"
                        imgMode="aspectFill"
                        top="0"
                    >
                    </up-icon>
                    </template>
                </up-cell>
            </up-cell-group>
        </view>
        <view
            class="flex box-border mt-0"
            style=""
            :style="{'url':'','position':'static','flexDirection':'column','justifyContent':'flex-start','alignItems':'flex-start','flex1':0,'width':'100%','height':'100%','padding':'0','margin':'1px 0px 5px 0px','color':'#333333','background':'#ffffffff','borderRadius':'0px','borderColor':'#ffffff','borderWidth':'0px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
        >
            <up-cell-group
                class=" mb-0 w-full"
                style=""
            >
                <up-cell
                    class=""
                    style=""
                    title="收藏"
                    :border="true"
                    :isLink="true"
                >
                    <template v-slot:icon>
                    <up-icon
                        class="mr-2"
                        style=""
                        name="https://s3.bmp.ovh/imgs/2024/11/25/fb8f1105c8fa2ebd.png"
                        color="#D2043EC7"
                        size="25px"
                        labelPos="right"
                        labelSize="20px"
                        labelColor="#606266"
                        space="3px"
                        imgMode="aspectFill"
                        width="23px"
                        height="22px"
                        top="0"
                    >
                    </up-icon>
                    </template>
                </up-cell>
                <up-cell
                    class=""
                    style=""
                    title="朋友圈"
                    :border="true"
                    :isLink="true"
                >
                    <template v-slot:icon>
                    <up-icon
                        class="mr-2"
                        style=""
                        name="photo"
                        color="#053FB3FF"
                        size="25px"
                        labelPos="right"
                        labelSize="15px"
                        labelColor="#606266"
                        space="3px"
                        imgMode="aspectFill"
                        top="0"
                    >
                    </up-icon>
                    </template>
                </up-cell>
                <up-cell
                    class=""
                    style=""
                    title="视频号"
                    :border="true"
                    :isLink="true"
                >
                    <template v-slot:icon>
                    <up-icon
                        class="mr-2"
                        style=""
                        name="play-right"
                        color="#F2A217FF"
                        size="25px"
                        labelPos="right"
                        labelSize="15px"
                        labelColor="#606266"
                        space="3px"
                        imgMode="aspectFill"
                        top="0"
                    >
                    </up-icon>
                    </template>
                </up-cell>
                <up-cell
                    class=""
                    style=""
                    title="卡包"
                    :border="true"
                    :isLink="true"
                >
                    <template v-slot:icon>
                    <up-icon
                        class="mr-2"
                        style=""
                        name="https://s3.bmp.ovh/imgs/2024/11/25/5e366b7336b482c2.png"
                        color="#22B202FF"
                        size="25px"
                        labelPos="right"
                        labelSize="15px"
                        labelColor="#606266"
                        space="3px"
                        imgMode="aspectFill"
                        width="22px"
                        height="22px"
                        top="0"
                    >
                    </up-icon>
                    </template>
                </up-cell>
                <up-cell
                    class=""
                    style=""
                    title="表情"
                    :isLink="true"
                >
                    <template v-slot:icon>
                    <up-icon
                        class="mr-2"
                        style=""
                        name="https://s3.bmp.ovh/imgs/2024/11/25/96be34667e6c9289.png"
                        color="#F2C822FF"
                        size="26px"
                        labelPos="right"
                        labelSize="25px"
                        labelColor="#606266"
                        space="3px"
                        imgMode="aspectFill"
                        width="22px"
                        height="22px"
                        top="0"
                    >
                    </up-icon>
                    </template>
                </up-cell>
            </up-cell-group>
        </view>
        <view
            class="flex box-border mb-3"
            style=""
            :style="{'url':'','position':'static','flexDirection':'column','justifyContent':'center','alignItems':'center','flex1':'auto','width':'100%','height':'100%','padding':'0','margin':'10px 0px 0px 0px','color':'#333333','background':'#ffffffff','borderRadius':'0px','borderColor':'#ffffff','borderWidth':'0px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
        >
            <up-cell-group
                class=" mt-0 mb-0 w-full"
                style=""
            >
                <up-cell
                    class=""
                    style=""
                    title="设置"
                    :isLink="true"
                >
                    <template v-slot:icon>
                    <up-icon
                        class="mr-2"
                        style=""
                        name="setting"
                        color="#256ADFFF"
                        size="25px"
                        labelPos="right"
                        labelSize="15px"
                        labelColor="#606266"
                        space="3px"
                        imgMode="aspectFill"
                        top="0"
                    >
                    </up-icon>
                    </template>
                </up-cell>
            </up-cell-group>
        </view>
    </view>
  </template>
  <script setup>
import { ref, reactive, watch, computed,
  onBeforeMount, onMounted, onBeforeUnmount, onUnmounted,
  onBeforeUpdate, onUpdated, nextTick, defineProps, toRaw } from 'vue'
import { onLoad, onShow } from '@dcloudio/uni-app'

// 页面加载
onLoad((options) => {
  loadData()
})

onShow(() => {
})

// 数据列表
const listLoading = ref(false)
const dataList = ref([])
const dataPage = ref({
  page: 0,
  limit: 10,
  total: 0
})

// 加载数据
const loadData = (refresh = false) => {
  listLoading.value = true
  // 请求接口
  listLoading.value = false
}

  </script>
