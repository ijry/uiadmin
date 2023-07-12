package com.jiangruyi.summer.core.service.impl;

import java.util.HashMap;
import java.util.Map;

import org.apache.commons.codec.digest.DigestUtils;

import com.jiangruyi.summer.core.service.IUserService;

import org.springframework.stereotype.Service;

@Service
public class UserServiceImpl implements IUserService{
    /**
	 * 根据username获取用户记录
     * @throws Exception
	 */
	public Map<String,Object> login(String username, String password) throws Exception {
        if (username.equals("admin")) {
            Map userInfo = new HashMap<String, Object>() {
                {
                    put("id", "1");
                    put("nickname", "管理员");
                    put("username", "admin");
                    put("password", "b898091b7529570e52c01ddad1520250");
                    put("avatar", "");
                    put("roles", "super_admin");
                }
            };
            // 验证密码
            String pwdsha1 = DigestUtils.sha1Hex(password.getBytes());
            String pwdmd5  = org.springframework.util.DigestUtils.md5DigestAsHex(pwdsha1.getBytes());
            // System.out.println(password + pwdmd5);
            if (!pwdmd5.equals(userInfo.get("password"))) {
                throw new Exception("密码不正确");
            }
            return userInfo;
        } else {
            throw new Exception("用户不存在");
        }
    }

    /**
	 * 根据用户id获取用户记录
     * @throws Exception
	 */
	public Map<String,Object> getById(String userId) throws Exception {
        if (userId.equals("1")) {
            Map userInfo = new HashMap<String, Object>() {
                {
                    put("id", "1");
                    put("nickname", "管理员");
                    put("username", "admin");
                    put("avatar", "");
                    put("roles", "super_admin");
                }
            };
            return userInfo;
        } else {
            throw new Exception("用户不存在");
        }
    }
}
