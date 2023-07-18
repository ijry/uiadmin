package com.jiangruyi.summer.core.security;

import com.jiangruyi.summer.core.entity.User;
 
import com.alibaba.fastjson.annotation.JSONField;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
 
import java.util.Collection;
import java.util.List;
import java.util.ArrayList;
import java.util.stream.Collectors;

/**
 * 实现UserDetails 作为封装类
 *
 因为UserDetailsService方法的返回值是UserDetails类型
 ，所以需要定义一个类，实现该接口，把用户信息封装在其中。
 */
@Data
@AllArgsConstructor
@NoArgsConstructor
public class LoginUser implements UserDetails {
    private User user;

    /**
     * 返回权限信息的
     * 在Security中，角色和权限共用GrantedAuthority接口，唯一的不同角色就是多了个前缀"ROLE_"，
     * 而且它没有Shiro的那种从属关系，即一个角色包含哪些权限等等。在Security看来角色和权限时一样的，
     * 它认证的时候，把所有权限（角色、权限）都取出来，而不是分开验证。
     */
    @Override
    public Collection<? extends GrantedAuthority> getAuthorities() {
        List<GrantedAuthority> grantedAuthorities = new ArrayList<>();
        for (String authority : user.getAuthorities()) {
            grantedAuthorities.add(new SimpleGrantedAuthority(authority));
        }
        return grantedAuthorities;
    }

    /**用来获取uid*/
    public String getId() {
        return user.getId();
    }

    /**用来获取密码*/
    @Override
    public String getPassword() {
        return user.getPassword();
    }
 
    @Override
    public String getUsername() {
        return user.getUsername();
    }

    /**判断是否没过期的*/
    @Override
    public boolean isAccountNonExpired() {
        return true;
    }

    /**判断是否过期的*/
    @Override
    public boolean isAccountNonLocked() {
        return true;
    }

    /** 凭证没过期*/
    @Override
    public boolean isCredentialsNonExpired() {
        return true;
    }

    /**判断是否可用*/
    @Override
    public boolean isEnabled() {
        return user.getStatus().equals(1);
    }
}
