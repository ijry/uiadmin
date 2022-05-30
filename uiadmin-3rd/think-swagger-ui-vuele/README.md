# think-swagger-ui-vuele
`swagger-ui`有非常多的版本，觉得不太好用，用`postman`，每个接口都要自己进行录入。所以在基于`think-vuele`进行了`swagger`格式`json`的解析，自己实现了一套swaggerui界面。

swagger分为后端数据提供方方和前端页面展示请求方。从一定角度来看，swagger是一种标准的数据格式的定义，对于不同语言进行实现一些注解API式的东西，能快速生成这种描述`restful`格式的`api`信息的`json`串.

此项目模块依赖于[`think-vuele`](http://vuele.tennetcn.com)

demo：[http://sw.tennetcn.com](http://sw.tennetcn.com)

github：[https://github.com/chfree/think-swagger-ui-vuele](https://github.com/chfree/think-swagger-ui-vuele)

## 使用方式
### 自行下载编译
```shell
// 下载代码
git clone https://github.com/chfree/think-swagger-ui-vuele

// 安装依赖
npm install

// 直接运行
npm run dev

// 打包
npm run build
```

### java项目 maven直接依赖
```xml
<dependency>
  <groupId>com.tennetcn.free</groupId>
  <artifactId>think-swagger-ui-starter</artifactId>
  <version>0.0.5</version>
</dependency>
```
此jar包的开源项目为[`think-free-base`](https://github.com/chfree/think-free-base/tree/master/think-swagger-ui-starter)中的子项目模块

# V1.1.1
## 更新功能点
- 显示`application/json`模式请求的参数描述
- 显示了响应示例描述

# V1.1.0
## 更新功能点
- 加入了请求响应的时间差
- 可以将某个api的请求响应信息以md的模式查看和导出
- 优化了部分显示内容



# V1.0.0

## 登陆
登陆界面分为`json`模式和`swagger`请求地址访问，没多大区别，只有拿到标准的`swagger`的`json`数据即可。

支持两种主题，一种是后端管理系统模式的主题。另外一种也是类似，中间1024px进行居中，两边留白。

![swagger_login](http://bedimage.tennetcn.com/tennetcn.com/project/swagger/swagger_login.png)

## 主页
对于我使用过的一个版本的`swagger`来说，当接口数量在`1000+`以上，会等的时间非常长，原因是他一次将所有接口数据进行解析渲染，这个就是慢的原因。

所以我将此进行优化，改为先解析出`api`摘要信息，然后在点击摘要的时候进行请求头、请求体的渲染；基本可以做到秒开

可以自动填充非`json`请求体的数据，采用的是`mock.Random`。

对于json请求体的数据，可以进行`json`格式化编辑，也是非常方便。`json`在线格式化编辑使用的是`josdejong`大神的[`jsoneditor`](https://github.com/josdejong/jsoneditor)

对于响应数据直接采用`json`格式化组件进行格式化展示，支持展开层级。再也不用将返回的数据在去找相关的`json`格式化工具进行格式化了。格式化控件采用的是`chenfengjw163`大神的[`vue-json-viewer`](https://github.com/chenfengjw163/vue-json-viewer)

![swagger_simple](http://bedimage.tennetcn.com/tennetcn.com/project/swagger/swagger_simple.png)

![swagger_edit_json](http://bedimage.tennetcn.com/tennetcn.com/project/swagger/swagger_edit_json.png)

![swagger_custom_field](http://bedimage.tennetcn.com/tennetcn.com/project/swagger/swagger_custom_field.png)

![swagger_admin](http://bedimage.tennetcn.com/tennetcn.com/project/swagger/swagger_admin.png)

## 设置
在后端api请求的时候，一般都会在请求头中带一些token的验证，来进行用户标识，所以在设置中，进行了自定义请求头的设置，可以方便的设置相关的请求头,在任何一个请求都会自动带上设置的请求信息。

![swagger_common_setting](http://bedimage.tennetcn.com/tennetcn.com/project/swagger/swagger_common_setting.png)

## swagger 信息展示
来源于后端swagger配置的相关信息在此处进行展示
![swagger_info](http://bedimage.tennetcn.com/tennetcn.com/project/swagger/swagger_info.png)
