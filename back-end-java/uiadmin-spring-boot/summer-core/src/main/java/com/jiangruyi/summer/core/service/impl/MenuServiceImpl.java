package com.jiangruyi.summer.core.service.impl;

import java.util.List;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import com.jiangruyi.summer.core.service.IMenuService;

import org.springframework.core.io.ClassPathResource;
import org.springframework.util.FileCopyUtils;

import org.springframework.stereotype.Service;

@Service
public class MenuServiceImpl implements IMenuService{
    /**
	 * 根据用户ID和角色获取用户有权限的菜单
     * @throws Exception
	 */
	public List<Object> getByUser(String userId, String userRoles) throws Exception {
        // 默认是从summer.json文件读取整个菜单，需要根据用户与角色显示不同菜单可以重写这个接口
        ClassPathResource classPathResource = new ClassPathResource("summer.json");
        byte[]  bytes= FileCopyUtils.copyToByteArray(classPathResource.getInputStream());
        String json = new String(bytes,"UTF-8");

        JSONObject jsTree = JSON.parseObject(json);

        return jsTree.getJSONArray("menu");
    }
}
