/**
 * 表单构建
 */
package com.jiangruyi.summer.core.util.xybuilder;

import java.util.List;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.io.Serializable;

import com.alibaba.fastjson.JSONArray;
import com.alibaba.fastjson.JSONObject;

/**
 * @author jry
 *
 */
public class XyBuilderForm implements Serializable {
    private static final long serialVersionUID = 1L;

    public Map<String, String> formType = new HashMap<String, String>(){{
        put("tabs", "TABS切换");
        put("hidden", "隐藏元素");
        put("static", "静态文本");
        put("link", "跳转链接");
        put("text", "单行文本");
        put("password", "密码");
        put("url", "URL网址");
        put("email", "邮箱");
        put("date", "日期");
        put("number", "数字");
        put("digit", "浮点型数字");
        put("tel", "手机号");
        put("textarea", "多行文本");
        put("array", "自定义数组");
        put("select", "下拉框");
        put("selects", "下拉框多选");
        put("radio", "单选框");
        put("checkbox", "多选框");
        put("switch", "开关");
        put("slider", "滑块");
        put("tags", "标签");
        put("datepicker", "日期");
        put("timepicker", "时刻");
        put("datetimepicker", "时间");
        put("daterangepicker", "日期区间");
        put("datetimerangepicker", "时间区间");
        put("rate", "星级评分");
        put("cascader", "级联选择");
        put("region", "省市区联动");
        put("colorpicker", "颜色选择器");
        put("image", "单图上传");
        put("imageflex", "单图列表");
        put("images", "多图上传");
        put("file", "单文件上传");
        put("files", "多文件上传");
        put("poster", "分享海报");
        put("selectlist", "列表选择器");
        put("checkboxtree", "树状表格复选");
        put("markdown", "Markdown");
        put("html", "HTML富文本");
        put("tinymce", "TinyMCE富文本");
        put("sku", "商品规格");
        put("fee", "运费模板");
    }};

    // 配置
    private Map<String, Object> config = new HashMap<String, Object>();

    // 表单提交方法
    private String formMethod = "post";

    // 分栏
    private JSONArray formCols = new JSONArray();

    // 分组
    private JSONArray formGroups = new JSONArray();

    // TABS
    private JSONArray formTabs = new JSONArray();

    // 表单项目
    private JSONArray formItems = new JSONArray();

    // 表单验证规则
    private JSONObject formRules = new JSONObject();

    // 表单值合集
    private Object itemValues = new HashMap<String, Object>();

    // 表单值合集
    private Object formValues = new HashMap<String, Object>();

    public XyBuilderForm() {
        this.config.put("continue", false);
        this.config.put("submitApi", "");
        this.config.put("itemDefaultPosition", "");
        this.config.put("submitButtonTitle", "确认");
        this.config.put("cancelButtonTitle", "取消");
        this.config.put("footerButtonLength", "120px");
        this.config.put("labelPosition", "left");
        this.config.put("labelWidth", "100px");
        this.config.put("defaultUploadDriver", "");
        this.config.put("defaultUploadAction", "/v1/core/index/upload/");
        this.config.put("defaultUploadMaxSize", 512);
    }

    // 修改设置
    public Object setConfig(String name, Object value){
        this.config.put(name, value);
        return this;
    }

    // 表单提交方法
    public Object setFormMethod(String method){
        this.formMethod = method;
        return this;
    }

    // 添加分栏
    public Object addFormCol(String name, Map<String,Object> span, List<Object> itemList, Map<String,Object> extra){
        JSONObject col = new JSONObject();
        col.put("name", name);
        col.put("span", span);
        col.put("itemList", itemList);
        col.put("extra", extra);
        this.formCols.add(col);
        return this;
    }

    // 添加分组
    public Object addFormGroup(String name,String title, List<Object> itemList, HashMap<String,Object> extra){
        JSONObject col = new JSONObject();
        col.put("name", name);
        col.put("title", title);
        col.put("itemList", itemList);
        col.put("extra", extra);
        this.formGroups.add(col);
        return this;
    }

    // 添加表单项目
    public Object addFormItem(String name, String title, String type, Object value){
        JSONObject col = new JSONObject();
        col.put("name", name);
        col.put("title", title);
        col.put("type", type);
        col.put("value", value);
        col.put("extra", new HashMap<>());
        this.formItems.add(col);
        return this;
    }

    // 添加表单项目
    public Object addFormItem(String name,String title, String type, Object value, Map<String, Object> extra){
        JSONObject col = new JSONObject();
        col.put("name", name);
        col.put("title", title);
        col.put("type", type);
        col.put("value", value);
        col.put("extra", extra);
        this.formItems.add(col);
        return this;
    }

    // 添加表单验证规则
    public Object addFormRule(String name, ArrayList<Object> rule){
        this.formRules.put(name, rule);
        return this;
    }

    // 设置表单值
    public Object setFormValues(Object data){
        this.itemValues = data;
        return this;
    }

    // 获取数据
	public Map<String, Object> getData() {
        HashMap<String, Object> data = new HashMap<String, Object>() {
            {
                put("alertList", new HashMap<String, Object>() {
                    {
                        put("top", new ArrayList<Object>());
                        put("bottom", new ArrayList<Object>());
                    }
                });
                put("formMethod", formMethod); 
                put("formCols", formCols);
                put("formGroups", formGroups);
                put("formTabs", formTabs);
                put("formItems", formItems);
                put("formRules", formRules);
                put("formValues", formValues);
                put("itemValues", itemValues);
                put("config", config);
            }
        };
		return data;
	}
}
