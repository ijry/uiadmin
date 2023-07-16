package com.jiangruyi.summer.core.service.impl;

import java.util.List;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import com.alibaba.fastjson.JSONArray;
import com.jiangruyi.summer.core.config.UserList;
import com.jiangruyi.summer.core.util.Tree;
import com.jiangruyi.summer.core.service.IMenuService;
import com.jiangruyi.summer.core.annotation.MenuItem;
import com.jiangruyi.summer.core.annotation.AnnotationUtil;
import io.github.classgraph.AnnotationParameterValue;
import io.github.classgraph.AnnotationParameterValueList;

import javax.annotation.Resource;
import org.springframework.core.io.ClassPathResource;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.util.FileCopyUtils;
import org.springframework.core.env.Environment;
import org.springframework.stereotype.Service;

@Service
public class MenuServiceImpl implements IMenuService{
    @Resource
	private Environment environment;

    @Autowired
	private UserList userList;

    /**
	 * 根据用户ID和角色获取用户有权限的菜单
     * @throws Exception
	 */
	public List<Object> getByUser(String userId, List<String> userAuthoritys) throws Exception {
        JSONArray menuTree = new JSONArray();
        if (environment.getProperty("summer.system.menu-from") != null
            && environment.getProperty("summer.system.menu-from").equals("file")) {
            // 默认是从summer.json文件读取整个菜单，需要根据用户与角色显示不同菜单可以重写这个接口
            ClassPathResource classPathResource = new ClassPathResource("summer.json");
            byte[]  bytes= FileCopyUtils.copyToByteArray(classPathResource.getInputStream());
            String json = new String(bytes,"UTF-8");
            JSONObject jsTree = JSON.parseObject(json);
            menuTree = jsTree.getJSONArray("menu");
        } else {
            // 从注解读取菜单
            JSONObject defaultRoot = new JSONObject();
            String title = "UiAdmin";
            if (environment.getProperty("summer.site.title") != null) {
                title = environment.getProperty("summer.site.title");
            }
            defaultRoot.put("title", title);
            defaultRoot.put("logo", environment.getProperty("summer.site.logo"));
            defaultRoot.put("logoTitle", environment.getProperty("summer.site.logoTitle"));
            defaultRoot.put("logoBadge", environment.getProperty("summer.site.logoBadge"));
            defaultRoot.put("path", "/default_root");
            defaultRoot.put("status", 1);

            // 获取注解中的菜单
            List<AnnotationParameterValueList> ctl = AnnotationUtil.methodAnnotionScan("*", MenuItem.class);
            JSONArray arr = new JSONArray();
            for (AnnotationParameterValueList each : ctl) {
                JSONObject tmp = new JSONObject();
                for (AnnotationParameterValue each1 : each) {
                    tmp.put(each1.getName(), each1.getValue());
                }
                if (tmp.getInteger("status") == 1
                    && tmp.getInteger("isHide") == 0) {
                    // 超级管理员或者其它有权限的角色
                    if (userId.equals("1")
                        || userAuthoritys.contains("ROLE_SUPER_ADMIN")
                        || userAuthoritys.contains(
                                "/" + tmp.getString("apiPrefix") + "/" + tmp.getString("menuLayer") + tmp.getString("path")
                            )
                        ) {
                        arr.add(tmp);
                    }
                }
            }
            JSONArray ret = Tree.listToTree(arr, "path", "pmenu", "children");
            // System.out.println(ret);

            defaultRoot.put("children", ret);

            menuTree.add(defaultRoot);
        }
        
        return menuTree;
    }
}
