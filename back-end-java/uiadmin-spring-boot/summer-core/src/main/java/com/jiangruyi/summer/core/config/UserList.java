package com.jiangruyi.summer.core.config;

import lombok.Data;
import java.util.List;
import org.springframework.boot.context.properties.ConfigurationProperties;
import org.springframework.stereotype.Component;
import com.jiangruyi.summer.core.entity.User;
import com.jiangruyi.summer.core.entity.Role;

@Component
@ConfigurationProperties(prefix = "summer.user") // 配置文件的前缀
@Data
public class UserList {
    // 用户列表
    private List<User> userList;

    // 用户角色
    private List<Role> userRole;
}
