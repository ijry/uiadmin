/**
 * 详情页构建
 */
package com.jiangruyi.summer.core.util.xybuilder;

import java.util.*;
import java.io.Serializable;

/**
 * @author jry
 *
 */
public class XyBuilderInfo implements Serializable {
    private static final long serialVersionUID = 1L;

    // 配置
    private Map<String, Object> config = new HashMap<String, Object>();

    // 数据列表
    private List<Object> infoList = new ArrayList<>();

    public XyBuilderInfo() {
        this.config.put("mode", "info");
    }

    // 修改设置
    public Object setConfig(String name, Object value){
        this.config.put(name, value);
        return this;
    }

    // 表单提交方法
    public Object addInfoGroup(Object grpData){
        infoList.add(grpData);
        return this;
    }

    // 获取数据
	public Map<String,Object> getData() {
        HashMap<String, Object> data = new HashMap<String, Object>() {
            {
                put("alertList", new HashMap<String, Object>() {
                    {
                        put("top", new ArrayList<Object>());
                        put("bottom", new ArrayList<Object>());
                    }
                });
                put("infoList", infoList);
                put("config", config);
            }
        };
		return data;
	}
}
