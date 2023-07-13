package com.jiangruyi.summer.core.controller;

import java.io.IOException;
import java.util.List;
import java.util.Map;

import com.alibaba.fastjson.JSONObject;
import com.jiangruyi.summer.core.util.ApiReturnUtil;
import com.jiangruyi.summer.core.service.IMenuService;
import com.jiangruyi.summer.core.service.IUserService;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import cn.dev33.satoken.stp.StpUtil;

/**
 * @author Jry
 */
@Controller
@RestController
@RequestMapping("/")
public class MenuController {
    @Autowired
    private IUserService userService;

    @Autowired
    private IMenuService menuService;
    /**
	 * 获取菜单接口
     * @throws IOException
	 */
	@GetMapping("/api/v1/admin/menu/trees")
	public Object trees() throws IOException {
        Map userInfo;
        try {
            // 获取当前会话登录id, 如果未登录，则抛出异常：`NotLoginException`
            userInfo = userService.getById((String) StpUtil.getLoginId());
        } catch (Exception e) {
            return ApiReturnUtil.error(401, e.getMessage());
        }
        List menuList;
        try {
            menuList = menuService.getByUser(userInfo.get("id").toString(), userInfo.get("roles").toString());
        } catch (Exception e) {
            return ApiReturnUtil.error(0, e.getMessage());
        }
        
        // 添加一层listData.listData
        JSONObject hash = new JSONObject();
        hash.put("dataList", menuList);
        JSONObject result = new JSONObject();
        result.put("listData", hash);
        result.put("menu2routes", true); // 将菜单参数作为页面路由

		return ApiReturnUtil.success(result);
    }
}