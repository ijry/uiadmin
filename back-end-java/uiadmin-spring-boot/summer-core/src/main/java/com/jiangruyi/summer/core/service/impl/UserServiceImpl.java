package com.jiangruyi.summer.core.service.impl;

import java.util.Map;
import java.util.HashMap;
import java.util.List;
import java.util.ArrayList;
import java.util.Arrays;

import org.apache.commons.codec.digest.DigestUtils;

import com.jiangruyi.summer.core.entity.User;
import com.jiangruyi.summer.core.entity.Role;
import com.jiangruyi.summer.core.service.IUserService;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.core.env.Environment;
import org.springframework.stereotype.Service;
import com.jiangruyi.summer.core.config.UserList;

import cn.dev33.satoken.stp.StpUtil;

@Service("CoreUserServiceImpl")
public class UserServiceImpl implements IUserService {
    @Autowired
	private Environment env;

    @Autowired
	private UserList userList;

    /**
	 * 获取用户列表
     * @throws Exception
	 */
    public List<Map<String, Object>> getUserListColumns() {
        // 获取用户列表
        List<User> myUserList = getUserList();
        List<Map<String, Object>> userList = new ArrayList();
        for (User userInfo : myUserList) {
            userList.add(new HashMap() {{
                put("title", userInfo.getNickname());
                put("value", userInfo.getId());
            }});
        }
        return userList;
    }

     /**
	 * 根据角色获取权限集合
     * @throws Exception
	 */
    public List<String> getAuthoritiesByUserRoles(String userRoles) {
        List<String> userRolesList = Arrays.asList(userRoles.split(","));
        String roleHasMenus = "";
        for (Role userRole : userList.getUserRole()) {
            for (String roleName : userRolesList) {
                if (userRole.getName().equals(roleName)
                    && !userRole.getMenus().equals("")) {
                    // 一个用户有多个角色将菜单权限整合
                    roleHasMenus = roleHasMenus + ",ROLE_" + roleName.toUpperCase();
                    roleHasMenus = roleHasMenus + ',' + userRole.getMenus();
                }
            }
        }
        List<String> menuUserRolesList =  Arrays.asList(roleHasMenus.split(","));
        return menuUserRolesList;
    }

    /**
	 * 根据username获取用户记录
     * @throws Exception
	 */
	public User getUserByUserName(String username) throws Exception {
        List<User> myUserList = getUserList();
        for (User userInfo : myUserList) {
            if (userInfo.getUsername().equals(username)) {
                // 获取用户角色权限
                userInfo.setAuthorities(getAuthoritiesByUserRoles(userInfo.getRoles()));
                return userInfo;
            }
        }

        throw new Exception("用户不存在");
    }

    /**
	 * 获取用户列表
     * @throws Exception
	 */
    private List<User> getUserList() {
        List<User> myUserList = userList.getUserList();
        if (myUserList == null) {
            User defaultUser = new User();
            defaultUser.setId("1");
            defaultUser.setNickname("admin");
            defaultUser.setUsername("admin");
            defaultUser.setPassword("uiadmin");
            defaultUser.setRoles("super_admin");
            myUserList = new ArrayList(){{
                add(defaultUser);
            }};
        }
        return myUserList;
    }

    /**
	 * 根据username获取用户记录
     * @throws Exception
	 */
	public User login(String username, String password) throws Exception {
        List<User> myUserList = getUserList();
        for (User userInfo : myUserList) {
            if (userInfo.getUsername().equals(username)) {
                // 验证密码
                String pwdsha1 = DigestUtils.sha1Hex(password.getBytes());
                String pwdmd5  = org.springframework.util.DigestUtils.md5DigestAsHex(pwdsha1.getBytes());
                // System.out.println(password + pwdmd5);
                if (!pwdmd5.equals(org.springframework.util.DigestUtils.md5DigestAsHex(DigestUtils.sha1Hex(userInfo.getPassword().getBytes()).getBytes()))) {
                    throw new Exception("密码不正确");
                }

                // 获取用户角色权限
                userInfo.setAuthorities(getAuthoritiesByUserRoles(userInfo.getRoles()));

                // 标记当前会话登录的账号id 
                StpUtil.login(userInfo.getId());

                // 获取当前会话的token值
                userInfo.setToken(StpUtil.getTokenValue());

                return userInfo;
            }
        }
        throw new Exception("用户不存在");
    }

    /**
	 * 根据用户id获取用户记录
     * @throws Exception
	 */
	public User getById(String userId) throws Exception {
        List<User> myUserList = getUserList();
        for (User userInfo : myUserList) {
            if (userInfo.getId().equals(userId)) {
                // 获取用户角色权限
                userInfo.setAuthorities(getAuthoritiesByUserRoles(userInfo.getRoles()));
                return userInfo;
            }
        }
        throw new Exception("用户不存在");
    }
}
