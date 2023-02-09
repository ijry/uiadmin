/**
 * 
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
public class XyBuilderList implements Serializable {
    private static final long serialVersionUID = 1L;
    
    // 顶部按钮
    private JSONArray topButtonList = new JSONArray();

    // 右侧按钮
    private JSONArray rightButtonList = new JSONArray();

    // 列
    private JSONArray columns = new JSONArray();

    // 数据列表
    private JSONArray dataList = new JSONArray();

    // 分页
    private HashMap<String, Object> dataPage = new HashMap<String, Object>();

    // 筛选表单项目
    private JSONArray filterItems = new JSONArray();

    // 配置
    private HashMap<String, Object> config = new HashMap<String, Object>();

    // 列表配置
    private HashMap<String, Object> dataListParams = new HashMap<String, Object>();
	
	public XyBuilderList() {
        this.config.put("listExpandAll", false);
        this.config.put("modalDefaultWidth", "800px");
        this.dataListParams.put("expandKey", "title");
        this.dataListParams.put("tableName", "");
        this.dataListParams.put("selectable", true);
        this.dataListParams.put("selectType", "checkbox");
    }

    // 添加顶部按钮
    public Object addTopButton(String name,String  title, HashMap pageData){
        JSONObject col = new JSONObject();
        col.put("name", name);
        col.put("title", title);
        col.put("pageData", pageData);
        col.put("style", new HashMap());
        this.topButtonList.add(col);
        return this;
    }

    // 添加顶部按钮
    public Object addTopButton(String name,String  title, HashMap pageData, HashMap style){
        JSONObject col = new JSONObject();
        col.put("name", name);
        col.put("title", title);
        col.put("pageData", pageData);
        col.put("style", style);
        this.topButtonList.add(col);
        return this;
    }

    // 添加右侧按钮
    public Object addRightButton(String name,String  title, HashMap pageData){
        JSONObject col = new JSONObject();
        col.put("name", name);
        col.put("title", title);
        col.put("pageData", pageData);
        col.put("style", new HashMap());
        this.rightButtonList.add(col);
        return this;
    }
    
    // 添加右侧按钮
    public Object addRightButton(String name,String  title, HashMap pageData, HashMap style){
        JSONObject col = new JSONObject();
        col.put("name", name);
        col.put("title", title);
        col.put("pageData", pageData);
        col.put("style", style);
        this.rightButtonList.add(col);
        return this;
    }

    // 添加列
    public Object addColumn(String name, String title){
        JSONObject col = new JSONObject();
        col.put("name", name);
        col.put("title", title);
        col.put("extra", new JSONArray());
        this.columns.add(col);
        return true;
    }

    // 添加列
    public Object addColumn(String name, String title, HashMap extra){
        JSONObject col = new JSONObject();
        col.put("name", name);
        col.put("title", title);
        col.put("extra", extra);
        this.columns.add(col);
        return true;
    }

    // 设置数据列表
    public Object setDataList(JSONArray data){
        this.dataList = data;
        return true;
    }

    // 设置分页
    public Object setDataPage(long total, long page, long limit){
        this.dataPage.put("total", total);
        this.dataPage.put("page", page);
        this.dataPage.put("limit", limit);
        return true;
    }

    // 添加搜索
    public Object addFilterItem(String name, String title, String type, Object value){
        JSONObject col = new JSONObject();
        col.put("name", name);
        col.put("title", title);
        col.put("type", type);
        col.put("value", value);
        col.put("extra", new HashMap());
        this.filterItems.add(col);
        return this;
    }

    // 添加搜索
    public Object addFilterItem(String name, String title, String type, Object value, HashMap extra){
        JSONObject col = new JSONObject();
        col.put("name", name);
        col.put("title", title);
        col.put("type", type);
        col.put("value", value);
        col.put("extra", extra);
        this.filterItems.add(col);
        return this;
    }

    // 修改列表设置
    public Object setListConfig(String name, Object value){
        this.config.put(name, value);
        return this;
    }

    // 修改设置
    public Object setConfig(String name, Object value){
        this.config.put(name, value);
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
                put("dataList", dataList); 
                put("dataListParams", dataListParams);
                put("topButtonList", topButtonList);
                put("rightButtonList", rightButtonList);
                put("columns", columns);
                put("dataPage", dataPage);
                put("filterItems", filterItems);
                put("filterValues", new ArrayList());
                put("filterExtra", new ArrayList());
                put("countList", new ArrayList());
                put("config", config);
            }
        };
		return data;
	}
}
