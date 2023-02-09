/**
 * 表单构建
 */
package com.jiangruyi.summer.core.util.xybuilder;

import java.util.ArrayList;
import java.util.HashMap;
import java.io.Serializable;

import com.alibaba.fastjson.JSONArray;
import com.alibaba.fastjson.JSONObject;

/**
 * @author jry
 *
 */
public class XyBuilderForm implements Serializable {
    private static final long serialVersionUID = 1L;

    // 配置
    private HashMap<String, Object> config = new HashMap<String, Object>();

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
    private HashMap itemValues = new HashMap();

    // 表单值合集
    private HashMap formValues = new HashMap();

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
    public Object addFormCol(String name,HashMap span, ArrayList itemList, HashMap extra){
        JSONObject col = new JSONObject();
        col.put("name", name);
        col.put("span", span);
        col.put("itemList", itemList);
        col.put("extra", extra);
        this.formCols.add(col);
        return this;
    }

    // 添加分组
    public Object addFormGroup(String name,String title, ArrayList itemList, HashMap extra){
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
        col.put("extra", new HashMap());
        this.formItems.add(col);
        return this;
    }

    // 添加表单项目
    public Object addFormItem(String name,String title, String type, Object value, HashMap extra){
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
    public Object addFormRule(String name, ArrayList rule){
        this.formRules.put(name, rule);
        return this;
    }

    // 设置表单值
    public Object setFormValues(HashMap data){
        this.itemValues = data;
        return this;
    }

    // 获取数据
	public HashMap getData() {
        HashMap<String, Object> data = new HashMap<String, Object>() {
            {
                put("alertList", new HashMap<String, Object>() {
                    {
                        put("top", new ArrayList());
                        put("bottom", new ArrayList());
                    }
                });
                put("formMethod", formMethod); 
                put("formCols", formCols);
                put("formGroups", formGroups);
                put("formTabs", formTabs);
                put("formItems", formItems);
                put("formRules", formRules);
                put("formValues", formValues);
                put("config", config);
            }
        };
		return data;
	}
}
