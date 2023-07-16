package com.jiangruyi.summer.core.service.impl;

import java.util.List;
import java.util.ArrayList;


import org.apache.commons.codec.digest.DigestUtils;

import com.jiangruyi.summer.core.entity.User;
import com.jiangruyi.summer.core.service.IUserService;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.core.env.Environment;
import org.springframework.stereotype.Service;
import com.jiangruyi.summer.core.config.UserList;

@Service
public class UserServiceImpl implements IUserService{
    @Autowired
	private Environment env;

    @Autowired
	private UserList userList;

     /**
	 * 根据角色获取权限集合
     * @throws Exception
	 */
    protected List<String> getAuthoritiesByUserRoles(String userRoles) {
        return new ArrayList(){{
            add("ROLE_SUPER_ADMIN");
        }};
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
