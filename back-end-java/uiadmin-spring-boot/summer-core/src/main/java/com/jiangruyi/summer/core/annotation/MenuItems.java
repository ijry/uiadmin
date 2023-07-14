package com.jiangruyi.summer.core.annotation;

import java.lang.annotation.*;

@Documented
@Retention(RetentionPolicy.CLASS)
@Target(ElementType.METHOD) // METHOD/FIELD/CONSTRUCTOR/TYPE几种注解类型
/*
 * 自动生成UIAdmin后台的菜单注解
 */
public @interface MenuItems {
    MenuItem[] value();
}

