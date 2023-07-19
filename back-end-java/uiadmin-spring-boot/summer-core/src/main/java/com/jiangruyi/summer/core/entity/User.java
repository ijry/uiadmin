package com.jiangruyi.summer.core.entity;

import java.util.List;
import java.lang.Cloneable;
import java.io.Serializable;
import lombok.Data;

/**
 * 用户信息实体类
 */
@Data
public class User implements Cloneable,Serializable {

    private static final long serialVersionUID = 1L;

    private String id;

    private String nickname;

    private String username;

    private String password;

    private String avatar;

    private String country = "+86";

    private String mobile;

    private String email;

    private String roles;

    private List<String> authorities;

    private Integer status = 1;

    // token
    private String token;

    @Override
    public Object clone() throws CloneNotSupportedException {
        return super.clone();
    }
}
