## Summer安装
只需要轻松的几步，即可立刻拥有一个完善的基于vue2+element的管理后台，并且全过程零前端代码需要，完全由JAVA来调用Builder自动构建的页面。

## 添加依赖
在您的spring-boot工程下的pom.xml文件中添加如下依赖

```xml
<dependency>
  <groupId>com.jiangruyi.summer</groupId>
  <artifactId>summer-core</artifactId>
  <version>1.2.0-RELEASE</version>
</dependency>
<!-- sa-token 权限认证, 在线文档：http://sa-token.dev33.cn/ -->
<dependency>
   <groupId>cn.dev33</groupId>
   <artifactId>sa-token-spring-boot-starter</artifactId>
   <version>1.34.0.RELEASE</version>
</dependency>
<dependency>
   <groupId>org.apache.commons</groupId>
   <artifactId>commons-lang3</artifactId>
   <version>3.8</version>
</dependency>
```

## 添加注解
在您的应用启动类中添加如下ComponentScan注解
```java
@SpringBootApplication
@ComponentScan("com.jiangruyi.summer.core") // 扫描核心模块的接口
public class SpringApplication {
	main() {
		...
	}
}
```
## 添加配置
在

```java
summer:
  site:
    # 网站名称
    title: "Summer"
    #正方形logo 
    logo: ""
    #带有标题的横logo 
    logoTitle: ""
    logoBadge: ""
  system:
    api-version: "1.0.0"
    menu-from: "annotation"
  user: 
    # 使用行为验证
    use-verify: "" #aj-captcha
    user-role:
      - id: 1
        name: super_admin
        title: 超级管理员
        menus: ""
        status: 1
    user-list:
      - id: 1
        nickname: "admin"
        username: "admin"
        password: "uiadmin"
        avatar: ""
        roles: "super_admin"
        status: 1

```


## 后台功能开发

uiadmin的菜单配置方式不同于传统在数据库添加的方式，而是采用了注解MenuItem的方式配置。

``` java
@MenuItem(title = "文章", path = "/post/lists", pmenu = "/content", menuType = 1,
    routeType = "list", apiSuffix = "", apiParams = "", apiMethod = "GET", sortnum = 0)
public ApiReturnObject lists(HttpServletRequest request) {
    // 使用Builder生成列表页面
    XyBuilderList listBuilder = new XyBuilderList();
    listBuilder.addTopButton("add", "新增", new HashMap<String, Object>() {{
        put("title", "新增");
        put("pageType", "drawer");
        put("modalType", "form");
        put("api", "/v1/admin/post/add");
        put("apiSuffix", new ArrayList());
        put("querySuffix", new ArrayList() {{
        }});
        put("width", "1000px");
    }});
    listBuilder.addColumn("title", "标题", new HashMap<String, Object>() {{   
        put("type", "");  
        put("width", "120");       
    }});        
    listBuilder.addColumn("cover", "封面", new HashMap<String, Object>() {{
        put("type", "image");
    }});
    listBuilder.setDataList(js);
    listBuilder.setDataPage(dataPage.getTotal(), dataPage.getCurrent(), dataPage.getSize());
    HashMap listData = listBuilder.getData();

    // 添加一层listData
    JSONObject result = new JSONObject();
    result.put("listData", listData);

    return ApiReturnUtil.success(result);
}

```

## 访问

上述步骤完成后理论上您只要启动您的应用，访问域名/xyadmin即可看到登录页面，输入admin和uiadmin即可登录成功进入后台。
注意菜单不显示的话，点击右上角清空缓存。
用户账号体系可以对接自己的账号体系，后边章节有具体方法。
