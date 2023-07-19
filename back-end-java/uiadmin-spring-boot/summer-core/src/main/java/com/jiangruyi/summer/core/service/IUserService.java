package com.jiangruyi.summer.core.service;

import java.util.List;
import java.util.Map;
import com.jiangruyi.summer.core.entity.User;
import org.springframework.stereotype.Service;

import com.baomidou.mybatisplus.extension.service.IService;

@Service("CoreUserService")
public interface IUserService {
    /**
	 * 根据用户名称获取用户记录
     * @throws Exception
	 */
	public User getUserByUserName(String userId) throws Exception;

	/**
	 * 获取用户列表
     * @throws Exception
	 */
    public List<Map<String, Object>> getUserListColumns() ;

    /**
	 * 根据用户名和密码登录
     * @throws Exception
	 */
    public User login(String username, String password) throws Exception;
    
    /**
	 * 根据用户id获取用户记录
     * @throws Exception
	 */
	public User getById(String userId) throws Exception;
}
