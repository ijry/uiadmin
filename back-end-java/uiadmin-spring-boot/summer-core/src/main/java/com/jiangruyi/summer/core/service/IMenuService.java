package com.jiangruyi.summer.core.service;

import java.util.List;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import com.alibaba.fastjson.JSONArray;
import com.jiangruyi.summer.core.util.Tree;
import com.jiangruyi.summer.core.annotation.AnnotationUtil;
import com.jiangruyi.summer.core.entity.MenuItem;

import io.github.classgraph.AnnotationParameterValue;
import io.github.classgraph.AnnotationParameterValueList;

import org.springframework.stereotype.Service;

@Service("CoreMenuService")
public interface IMenuService {
	/**
	 * 获取所有菜单
     * @throws Exception
	 */
    public static JSONArray getAllMenus() {
        List<AnnotationParameterValueList> ctl = AnnotationUtil.methodAnnotionScan("*", com.jiangruyi.summer.core.annotation.MenuItem.class);
        List<MenuItem> arr = new ArrayList();
        for (AnnotationParameterValueList each : ctl) {
            JSONObject tmp = new JSONObject();
            for (AnnotationParameterValue each1 : each) {
                tmp.put(each1.getName(), each1.getValue());
            }
            if (tmp.getInteger("status") == 1
                && tmp.getInteger("isHide") == 0) {
                tmp.put("fullPath", "/" + tmp.getString("apiPrefix")
                    + "/" + tmp.getString("menuLayer") + tmp.getString("path"));
                arr.add(tmp.toJavaObject(MenuItem.class));
            }
            
        }
        // 使用匿名比较器排序
        Collections.sort(arr, new Comparator<MenuItem>() {
            @Override
            public int compare(MenuItem p1, MenuItem p2) {
                return p2.getSortnum() - p1.getSortnum();
            }
        });
        JSONArray ret = Tree.listToTree(JSONArray.parseArray(JSON.toJSONString(arr)), "path", "pmenu", "children");
        return ret;
    }

    /**
	 * 根据用户ID和角色获取用户有权限的菜单
     * @throws Exception
	 */
	public List<Object> getByUser(String userId, List<String> userAuthoritys) throws Exception;
}
