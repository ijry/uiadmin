package com.jiangruyi.summer.core.util.xybuilder;

import java.util.HashMap;
import java.io.Serializable;

import com.alibaba.fastjson.JSONArray;
import com.alibaba.fastjson.JSONObject;

/**
 * @author jry
 *
 */
public class XyBuilderTab implements Serializable {
    private static final long serialVersionUID = 1L;

    // 数据
    private JSONObject data;

    /**
     * 初始化
     * @author jry <ijry@qq.com>
     */
    public XyBuilderTab() {
        data.put("tabList", new JSONArray());
        data.put("tabType", "card");
    }

    /**
     * 添加一个Tab
     * @author jry <ijry@qq.com>
     */
    public Object addTab(
        String title,
        JSONArray list
    ) {
        JSONArray tabList = data.getJSONArray("tabList");
        tabList.add(new HashMap<String, Object>(){{
            put("title", title);
            put("list", list);
        }});
        data.put("tabList", tabList);
        return this;
    }

    /**
     * 批量添加
     * @author jry <ijry@qq.com>
     */
    public Object addTabs(JSONArray tabs) {
        for(int i=0; i < tabs.size(); i++){
            JSONObject obj = (JSONObject)tabs.get(i);
            addTab(obj.getString("title"), obj.getJSONArray("list"));
        }
        return this;
    }

    /**
     * 设置Tab样式
     * @author jry <ijry@qq.com>
     */
    public Object setType() {
        data.put("tabType", "line");
        return this;
    }

    /**
     * 设置Tab样式
     * @author jry <ijry@qq.com>
     */
    public Object setType(String type) {
        data.put("tabType", type);
        return this;
    }

    /**
     * 返回数据
     * @author jry <ijry@qq.com>
     */
    public JSONObject getData() {
        return data;
    }
}
