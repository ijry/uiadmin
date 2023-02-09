package com.jiangruyi.summer.core.service;

import java.util.Map;

import org.springframework.stereotype.Service;

@Service
public interface IUserService {
    /**
	 * 根据用户名和密码登录
     * @throws Exception
	 */
    public Map<String,Object> login(String username, String password) throws Exception;
    
    /**
	 * 根据用户id获取用户记录
     * @throws Exception
	 */
	public Map<String,Object> getById(String userId) throws Exception;
}
