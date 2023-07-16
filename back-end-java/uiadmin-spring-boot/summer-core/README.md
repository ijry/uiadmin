# 简介

uiadmin的spring-boot实现版本，无需进行vue开发即可实时动态生成管理后台。

![UiAdmin列表](https://raw.githubusercontent.com/ijry/uiadmin/master/back-end-js/uiadmin-express/uiadmin-core/preview/lists.png)

## Builder文档

https://uiadmin.net/docs/builder


## 官方脚手架

### 下载脚手架工程

如果您不想自己搭建项目工程，可以直接使用官方的脚手架。

https://gitee.com/uiadmin/uiadmin/tree/master/back-end-java/

### 下载后执行

```
mvn spring-boot:run
```
访问：localhost:8080/xyadmin/
输入账号admin密码uiadmin登录即可进入管理后台页面


### 开发

仿照DemoController进行业务开发


## 自建工程使用步骤

### 引入依赖

在pom.xml新增如下配置
```
<properties>
    ...
    <summer.version>1.2.0</summer.version>
</properties>
...
<dependencyManagement>
    ...
    <dependencies>
        ...
        <!-- 导入uiadmin的依赖 -->
        <dependency>
            <groupId>com.jiangruyi.summer</groupId>
            <artifactId>summer-core</artifactId>
            <version>${summer.version}</version>
            <type>pom</type>
            <scope>import</scope>
        </dependency>
    </dependencies>
</dependencyManagement>

<dependencies>
    ...
    <!-- uiadmin/summer -->
    <dependency>
        <groupId>com.jiangruyi.summer</groupId>
        <artifactId>summer-core</artifactId>
        <version>${summer.version}</version>
    </dependency>
</dependencies>

```

###

在XXXApplication里新增ComponentScan路径

```
import org.springframework.context.annotation.ComponentScan;

@SpringBootApplication
@ComponentScan("com.jiangruyi.summer.*")
```


### 配置文件

application.yml新增如下配置

```
uiadmin:
  site:
    # 网站名称
    title: "UiAdmin"
    #正方形logo 
    logo: ""
    #带有标题的横logo 
    logoTitle: ""
    logoBadge: ""
  system:
    api-version: "1.0.0"
    menu-from: "annotation"
  user: 
    user-role:
      - id: 1
        name: super_admin
        title: 超级管理员
        menus: ""
        status: 1
      - id: 2
        name: admin
        title: 管理员
        menus:
          - "/v1/admin/demo/lists"
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

### 初始化
app.js请参考如下代码
```
### 启动

```
mvn spring-boot:run
```

访问{host:端8080}/xyadmin/

输入账号admin密码uiadmin登录即可进入管理后台页面


### 开发

仿照DemoController进行业务开发

