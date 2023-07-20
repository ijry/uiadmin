package com.jiangruyi.summer.core.entity;

import java.lang.Cloneable;
import java.io.Serializable;
import lombok.Data;

/**
 * 菜单实体类
 */
@Data
public class MenuItem implements Cloneable,Serializable {

    private static final long serialVersionUID = 1L;

    private String title;

    private String icon;

    private String path;

    private String pmenu;

    private String tip;

    private String menuLayer;

    private int menuType; // -1顶级文件夹0文件夹1菜单2按钮3接口

    private String routeType; // form/list/info/stack/tab

    private String apiPrefix;

    private String apiSuffix;

    private String apiParams;

    private String apiMethod;

    private String apiExt;

    private int isHide;

    private int status;

    private int sortnum;

    private String pathSuffix;

    private String outUrl;

    private String fullPath;
}
