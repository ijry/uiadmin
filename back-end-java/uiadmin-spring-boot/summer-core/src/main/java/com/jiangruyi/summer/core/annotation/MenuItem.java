package com.jiangruyi.summer.core.annotation;

import java.lang.annotation.*;

@Documented
@Retention(RetentionPolicy.CLASS)
@Target(ElementType.METHOD) // METHOD/FIELD/CONSTRUCTOR/TYPE几种注解类型
@Repeatable(MenuItems.class)
/*
 * 自动生成UIAdmin后台的菜单注解
 * example use 
 * @MenuItem(title = "菜单", path = "", pmenu = "", menuType = 1, routeType = "list",
 *     apiSuffix = "", apiParams = "", apiMethod = "GET", sortnum = 0)
 */
public @interface MenuItem {

    String title() default "未命名菜单";

    String icon() default "";

    String path() default "";

    String pmenu() default "";

    String tip() default "";

    String menuLayer() default "admin";

    int menuType() default 1; // -1顶级文件夹0文件夹1菜单2按钮3接口

    String routeType() default "form"; // form/list/info/stack/tab

    String apiPrefix() default "v1";

    String apiSuffix() default "";

    String apiParams() default "";

    String apiMethod() default "GET";

    String apiExt() default "";

    int isHide() default 0;

    int status() default 1;

    int sortnum() default 0;

    String pathSuffix() default "";

    String outUrl() default "";

}

