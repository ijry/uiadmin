
  <template>
    <view
        class="fv-page flex flex-col px-4"
        style=""
    >
        <view
            class="flex box-border"
            style=""
            :style="{'url':'','position':'static','flexDirection':'column','justifyContent':'center','alignItems':'center','flex1':1,'width':'100%','height':'100vh','padding':'10px 10px 10px 10px','margin':'0px 0px 0px 0px','color':'#333333','background':'linear-gradient(177deg, #5820f7ff 0%,#78a9faff 100%)','borderRadius':'0px','borderColor':'#ffffff','borderWidth':'1px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
        >
            <view
                class="flex box-border"
                style=""
                :style="{'url':'','position':'static','flexDirection':'column','justifyContent':'center','alignItems':'center','flex1':'auto','width':'98%','height':'50px','padding':'10px 10px','margin':'0px 0px -40px 0px','color':'#333333','background':'#ffffffff','borderRadius':'10px','borderColor':'#ffffff','borderWidth':'0px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
            >
            </view>
            <view
                class="flex box-border  shadow-sm"
                style=""
                :style="{'url':'','position':'static','flexDirection':'column','justifyContent':'center','alignItems':'center','flex1':'auto','width':'100%','height':'400px','padding':'90px 10px 100px 10px','margin':'0px 0px 0px 0px','color':'#333333','background':'#ffffffff','borderRadius':'10px','borderColor':'#F4F1F1FF','borderWidth':'1px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
            >
                <view
                    class="flex box-border"
                    style=""
                    :style="{'url':'','position':'static','flexDirection':'row','justifyContent':'center','alignItems':'center','flex1':'auto','width':'100%','height':'100%','padding':'10px 10px 10px 10px','margin':'0px 0px 0px 0px','color':'#333333','background':'#ffffff63','borderRadius':'4px','borderColor':'#ffffff','borderWidth':'0px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
                >
                    <view
                        class="flex box-border"
                        style=""
                        :style="{'url':'','position':'static','flexDirection':'column','justifyContent':'center','alignItems':'center','flex1':'auto','width':'100%','height':'100%','padding':'10px 10px 10px 10px','margin':'0px 0px 0px 0px','color':'#333333','background':'#ffffff63','borderRadius':'0px','borderColor':'#ffffff','borderWidth':'0px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
                    >
                        <up-text
                            class=""
                            style=""
                            color="#303133"
                            size="23px"
                            text="欢迎登录"
                            :flex1="true"
                            align="left"
                            wordWrap="normal"
                            :show="true"
                            :bold="true"
                            iconStyle="15px"
                            decoration="none"
                        >
                        </up-text>
                    </view>
                </view>
                <view
                    class="flex box-border"
                    style=""
                    :style="{'url':'','position':'static','flexDirection':'column','justifyContent':'center','alignItems':'center','flex1':'auto','width':'100%','height':'auto','padding':'10px 10px 10px 10px','margin':'0px 0px 0px 0px','color':'#333333','background':'#ffffff63','borderRadius':'0px','borderColor':'#ffffff','borderWidth':'0px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
                >
                    <view
                        class="flex box-border"
                        style=""
                        :style="{'url':'','position':'static','flexDirection':'row','justifyContent':'center','alignItems':'center','flex1':'auto','width':'100%','height':'100%','padding':'10px 10px 10px 10px','margin':'0px 0px 0px 0px','color':'#333333','background':'#ffffff63','borderRadius':'0px','borderColor':'#ffffff','borderWidth':'0px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
                    >
                        <up-input
                            class=""
                            style=""
                            type="text"
                            disabledColor="#f5f7fa"
                            maxlength="140"
                            placeholder="服务器地址"
                            placeholderClass="input-placeholder"
                            placeholderStyle="color: #c0c4cc"
                            confirmType="done"
                            cursor="-1"
                            cursorSpacing="30"
                            selectionStart="-1"
                            selectionEnd="-1"
                            :adjustPosition="true"
                            inputAlign="left"
                            fontSize="15px"
                            color="#303133"
                            prefixIcon="setting"
                            border="bottom"
                            shape="square"
                        >
                        </up-input>
                    </view>
                    <view
                        class="flex box-border"
                        style=""
                        :style="{'url':'','position':'static','flexDirection':'row','justifyContent':'center','alignItems':'center','flex1':'auto','width':'100%','height':'100%','padding':'10px 10px 10px 10px','margin':'0px 0px 0px 0px','color':'#333333','background':'#ffffff63','borderRadius':'0px','borderColor':'#ffffff','borderWidth':'0px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
                    >
                        <up-input
                            class=""
                            style=""
                            type="text"
                            disabledColor="#f5f7fa"
                            maxlength="140"
                            placeholder="请输入账号"
                            placeholderClass="input-placeholder"
                            placeholderStyle="color: #c0c4cc"
                            confirmType="done"
                            cursor="-1"
                            cursorSpacing="30"
                            selectionStart="-1"
                            selectionEnd="-1"
                            :adjustPosition="true"
                            inputAlign="left"
                            fontSize="15px"
                            color="#303133"
                            prefixIcon="account"
                            border="bottom"
                            shape="square"
                        >
                        </up-input>
                    </view>
                    <view
                        class="flex box-border"
                        style=""
                        :style="{'url':'','position':'static','flexDirection':'row','justifyContent':'center','alignItems':'center','flex1':'auto','width':'100%','height':'100%','padding':'10px 10px 10px 10px','margin':'0px 0px 0px 0px','color':'#333333','background':'#ffffff63','borderRadius':'0px','borderColor':'#ffffff','borderWidth':'0px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
                    >
                        <up-input
                            class=""
                            style=""
                            type="text"
                            disabledColor="#f5f7fa"
                            maxlength="140"
                            placeholder="请输入密码"
                            placeholderClass="input-placeholder"
                            placeholderStyle="color: #c0c4cc"
                            confirmType="done"
                            cursor="-1"
                            cursorSpacing="30"
                            selectionStart="-1"
                            selectionEnd="-1"
                            :adjustPosition="true"
                            inputAlign="left"
                            fontSize="15px"
                            color="#303133"
                            prefixIcon="lock"
                            border="bottom"
                            shape="square"
                        >
                        </up-input>
                    </view>
                    <view
                        class="flex box-border"
                        style=""
                        :style="{'url':'','position':'static','flexDirection':'row','justifyContent':'center','alignItems':'center','flex1':'auto','width':'100%','height':'100%','padding':'10px 10px 10px 10px','margin':'20px 0px 0px 0px','color':'#333333','background':'#ffffff00','borderRadius':'0px','borderColor':'#ffffff','borderWidth':'0px','borderStyle':'solid','top':'','bottom':'','left':'','right':''}"
                    >
                        <up-button
                            class=""
                            style=""
                            text="登录"
                            type="primary"
                            color="#4D67FFFF"
                            shape="circle"
                            size="normal"
                        >
                        </up-button>
                    </view>
                </view>
            </view>
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
