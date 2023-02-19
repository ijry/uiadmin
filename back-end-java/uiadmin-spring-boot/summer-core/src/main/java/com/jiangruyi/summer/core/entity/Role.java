package com.jiangruyi.summer.core.entity;

import java.lang.Cloneable;
import java.io.Serializable;
import lombok.Data;

/**
 * 用户角色实体类
 */
@Data
public class Role implements Cloneable,Serializable {

    private static final long serialVersionUID = 1L;

    private String id;

    private String name;

    private String title;

    private String menus;

    private Integer status;

    @Override
    public Object clone() throws CloneNotSupportedException {
        return super.clone();
    }
}
