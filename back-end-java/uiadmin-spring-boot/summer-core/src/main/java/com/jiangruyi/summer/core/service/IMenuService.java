package com.jiangruyi.summer.core.service;

import java.util.List;

import org.springframework.stereotype.Service;

@Service
public interface IMenuService {
    /**
	 * 根据用户ID和角色获取用户有权限的菜单
     * @throws Exception
	 */
	public List<Object> getByUser(String userId, List<String> userAuthoritys) throws Exception;
}
