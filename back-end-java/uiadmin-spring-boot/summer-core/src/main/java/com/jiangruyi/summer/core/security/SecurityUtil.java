package com.jiangruyi.summer.core.security;

import javax.annotation.Resource;
import javax.annotation.PostConstruct;

import cn.dev33.satoken.stp.StpUtil;

import com.jiangruyi.summer.core.service.IUserService;

import com.jiangruyi.summer.core.entity.User;
import com.jiangruyi.summer.core.security.LoginUser;
import org.springframework.core.env.Environment;
import org.springframework.stereotype.Component;
import org.springframework.security.core.Authentication;
import org.springframework.security.authentication.AnonymousAuthenticationToken;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
 
/**
 * 授权工具类
 */
@Component
public class SecurityUtil {

    @Autowired
    private IUserService userService;
    private static IUserService SUserService;

    @Value("${summer.auth.driver:sa-token}")
    private String driver;
    private static String Sdriver;
    @PostConstruct
    public void setStaticParams() {
        Sdriver = this.driver;
        SUserService = this.userService;
    }

    /**
     * 获取当前登录用户uid
     * @return
     */
    public static String getLoginId() {
        LoginUser userLoginInfo = getLoginInfo();
        return userLoginInfo.getId();
    }

    /**
     * 获取当前登录用户信息
     * @return
     */
    public static LoginUser getLoginInfo() {
        LoginUser userLoginInfo = new LoginUser();
        System.out.println("当前auth-driver是" + Sdriver);
        switch (Sdriver) {
            case "spring-security":
                Authentication authentication = SecurityContextHolder.getContext().getAuthentication();
                if (!(authentication instanceof AnonymousAuthenticationToken)) {
                    Object principal = authentication.getPrincipal();
                    // System.out.println("当前principal是" + principal.toString());
                    userLoginInfo = (LoginUser) principal;
                } else {
                    throw new RuntimeException("请先登录");
                }
                break;
            case "sa-token":
                // 获取当前会话登录id, 如果未登录，则抛出异常：`NotLoginException`
                String userId = (String)StpUtil.getLoginId();
                User userInfo = new User();
                try {
                    userInfo = SUserService.getById(userId);
                } catch (Exception e) {
                    throw new RuntimeException("请先登录");
                }
                userLoginInfo.setUser(userInfo); 
                break;
            default:
                throw new RuntimeException("用户认证驱动未设置");
        }
        return userLoginInfo;
    }

}
 