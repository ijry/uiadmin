<template>
  <div>
    <FormItem :label="item.title" :prop="prop">
        <!-- 分割线 -->
        <template v-if="item.type == 'divider'">
            <Divider />
        </template>
        <!-- 静态文本 -->
        <template v-if="item.type == 'static'">
            <span>{{item.value}}</span>
        </template>
        <!-- 文本框 -->
        <template v-if="item.type == 'text'">
            <Input v-model="form_values[item.name]" :placeholder="item.extra.placeholder"></Input>
        </template>
        <!-- 多行文本 -->
        <template v-else-if="item.type == 'textarea'">
            <Input v-model="form_values[item.name]" type="textarea" :autosize="{minRows: 2,maxRows: 5}" :placeholder="item.extra.placeholder"></Input>                    
        </template>
        <!-- 自定义数组 -->
        <template v-else-if="item.type == 'array'">
            <Input v-model="form_values[item.name]" type="textarea" :autosize="{minRows: 2,maxRows: 5}" :placeholder="item.extra.placeholder"></Input>                    
        </template>
        <!-- 下拉框 -->
        <template v-else-if="item.type == 'select'">
            <Select v-model="form_values[item.name]">
                <Option v-for="(item1,key1,index1) in item.extra.options" :key="index1" :value="item1.value">
                    {{item1.title}}
                </Option>
            </Select>
        </template>
        <!-- 单选框 -->
        <template v-else-if="item.type == 'radio'">
            <RadioGroup v-model="form_values[item.name]">
                <Radio v-for="(item1,key1,index1) in item.extra.options" :key="index1" :label="item1.value">
                    <span>{{item1.title}}</span>
                </Radio>
            </RadioGroup>
        </template>
        <!-- 多选框 -->
        <template v-else-if="item.type == 'checkbox'">
            <CheckboxGroup v-model="form_values[item.name]">
                <Checkbox v-for="(item1,key1,index1) in item.extra.options" :key="index1" :label="item1.title"></Checkbox>
            </CheckboxGroup>
        </template>
        <!-- 开关 -->
        <template v-else-if="item.type == 'switch'">
            <i-switch v-model="form_values[item.name]" size="large">
                <span slot="open">{{item1.extra.options[0].title}}</span>
                <span slot="close">{{item1.extra.options[1].title}}</span>
            </i-switch>
        </template>
        <!-- 滑块 -->
        <template v-else-if="item.type == 'slider'">
            <Slider v-model="form_values[item.name]" range></Slider>
        </template>
        <!-- 日期选择 -->
        <template v-else-if="item.type == 'datepicker'">
            <DatePicker type="date" placeholder="选择日期" v-model="iform_values[item.name]"></DatePicker>
        </template>
        <!-- 时间选择 -->
        <template v-else-if="item.type == 'timepicker'">
            <TimePicker type="time" placeholder="选择时间" v-model="form_values[item.name]"></TimePicker>
        </template>
        <!-- 日期时间选择 -->
        <template v-else-if="item.type == 'datetimepicker'">
            <Row>
                <Col span="11">
                    <DatePicker type="date" placeholder="选择日期" v-model="form_values[item.name][0]"></DatePicker>
                </Col>
                <Col span="2" style="text-align: center">-</Col>
                <Col span="11">
                    <TimePicker type="time" placeholder="选择时间" v-model="form_values[item.name][1]"></TimePicker>
                </Col>
            </Row>
        </template>
        <!-- 评分 -->
        <template v-else-if="item.type == 'rate'">
            <Rate v-model="form_values[item.name]" />
        </template>
        <!-- 级联选择 -->
        <template v-else-if="item.type == 'cascader'">
            <Cascader :data="item.extra.options" v-model="form_values[item.name]" size="large"></Cascader>
        </template>
        <!-- 颜色选择器 -->
        <template v-else-if="item.type == 'colorpicker'">
            <ColorPicker v-model="form_values[item.name]" />
        </template>
        <!-- 单文件上传 -->
        <template v-else-if="item.type == 'file'">
            <Upload
                type="drag"
                :action="item.action">
                <div style="padding: 20px 0">
                    <Icon type="ios-cloud-upload" size="42" style="color: #3399ff"></Icon>
                    <p>点击或者拖动文件到此处上传</p>
                </div>
            </Upload>
        </template>
        <!-- 多文件上传 -->
        <template v-else-if="item.type == 'files'">
            <Upload
                multiple
                type="drag"
                :action="item.action">
                <div style="padding: 20px 0">
                    <Icon type="ios-cloud-upload" size="42" style="color: #3399ff"></Icon>
                    <p>点击或者拖动文件到此处上传</p>
                </div>
            </Upload>
        </template>
        <template v-else-if="item.type == 'checkboxtree'">
            <!-- https://github.com/lison16/tree-table-vue -->
            <tree-table
                :ref="'checkboxtree_' + item.name"
                :expand-key="item.extra.expand_key"
                :is-fold="true"
                :border="true"
                :stripe="false"
                :selectable="true"
                :expand-type="false"
                :columns="item.extra.columns"
                :data="item.extra.data">
            </tree-table>
        </template>
        <template v-else-if="item.type == 'formlist'">
            <Row>
                <div v-for="(item1,key1,index1) in item.extra.options" :key="index1">
                    <Col :span="item1.span">
                        {{item1.title}}
                    </Col>
                </div>
            </Row>
            <Row v-for="(item2,key2,index2) in form_values[item.name]" :key="index2">
                <div v-for="(item3,key3,index3) in item.extra.options" :key="index3">
                    <Col :span="item3.span">
                        <Input v-model="form_values[item.name][key2][item3.value]"></Input>
                    </Col>
                </div>
                <Button type="dashed" @click="formlist_delrow(item.name)">删除</Button>
            </Row>
            <Button type="dashed" icon="md-add" @click="formlist_addrow(item.name)" style="margin-top: 8px;">增加一行</Button>
        </template>
        <div style="color: #aaa;font-size: 12px;">{{item.extra.tip}}</div>
    </FormItem>
  </div>
</template>

<script>
export default {
    name: 'va_dyform_item',
    props: {
        prop: '',
        item: {},
        form_values: {}
    },
    created: function(){
    },
    beforeMount: function(){
    },
    methods: {
        formlist_addrow (name) {
            this.form_values[name].push(new Object());
        },
        getChecked (name) {
            return this.$refs['checkboxtree_' + this.item.name].getCheckedProp(name)
        }
    },
    watch: {
    }
}
</script>

<style>
</style>
