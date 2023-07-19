package com.jiangruyi.summer.core.controller;

import java.util.HashMap;
import java.util.Map;

import javax.annotation.Resource;
import javax.validation.Valid;
import javax.validation.constraints.NotBlank;

import com.alibaba.fastjson.JSONObject;
import com.anji.captcha.model.common.ResponseModel;
import com.anji.captcha.model.vo.CaptchaVO;
import com.anji.captcha.service.CaptchaService;
import com.jiangruyi.summer.core.util.ApiReturnUtil;
import com.jiangruyi.summer.core.util.ApiReturnObject;
import com.jiangruyi.summer.core.service.IUserService;

import org.apache.commons.codec.digest.DigestUtils;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.core.env.Environment;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import com.jiangruyi.summer.core.security.SecurityUtil;
import com.jiangruyi.summer.core.security.LoginUser;
import com.jiangruyi.summer.core.entity.User;

import cn.dev33.satoken.stp.StpUtil;
/**
 * @author Jry
 */
@RestController("CoreUserController")
@RequestMapping("/")
public class UserController {
    @Autowired
    private IUserService userService;

    @Resource
	private Environment environment;

    @Autowired(required = false)
    private CaptchaService captchaService;

    /**
	 * 登录
	 */
	@PostMapping("/api/v1/admin/user/login")
	public ApiReturnObject login(@Valid @NotBlank @RequestBody JSONObject data) {
        // 行为验证
        if (environment.getProperty("summer.user.useVerify") != null
            && environment.getProperty("summer.user.useVerify") != ""
            && captchaService != null) {
            switch (environment.getProperty("summer.user.useVerify")) {
                case "aj-captcha":
                    //必传参数：captchaVO.captchaVerification
                    CaptchaVO  cvo = new CaptchaVO();
                    cvo.setCaptchaVerification(data.getJSONObject("captchaVerify").getString("captchaVerification"));
                    ResponseModel response = captchaService.verification(cvo);
                    if(response.isSuccess() == false){
                        return ApiReturnUtil.error(0, response.getRepCode() + response.getRepMsg());
                    }
                    break;
                default:
                    break;
            }
        }

        // 登录
        String account = data.getString("account");
        String password = data.getString("password");
        User userInfo = new User();
        try {
            userInfo = userService.login(account, password);
        } catch (Exception e) {
            return ApiReturnUtil.error(0, e.getMessage());
        }

        HashMap<String, Object> result = new HashMap<String, Object>();
        result.put("token", "Bearer " + userInfo.getToken());
        userInfo.setPassword("");
        result.put("userInfo", userInfo);
		return ApiReturnUtil.success("登录成功", result);
    }

    /**
	 * 获取当前登录的用户信息接口
	 */
	@GetMapping("/api/v1/admin/user/info")
	public ApiReturnObject userInfo() {
        User userInfo = SecurityUtil.getLoginInfo().getUser();
        JSONObject result = new JSONObject();
        userInfo.setPassword("");
        result.put("userInfo", userInfo);
		return ApiReturnUtil.success(result);
	}
    
    /**
	 * 注销登录
	 */
	@DeleteMapping("/api/v1/core/user/logout")
	public ApiReturnObject logout() {
        // 当前会话注销登录
        StpUtil.logout();

        JSONObject result = new JSONObject();
        result.put("msg", "Success");
		return ApiReturnUtil.success(result);
	}
}
