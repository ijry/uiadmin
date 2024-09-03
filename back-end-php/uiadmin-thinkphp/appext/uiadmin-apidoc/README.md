# uniadmin-apidoc

![uniadmin](https://vkceyugu.cdn.bspapp.com/VKCEYUGU-f12e1180-fce8-465f-a4cd-9f2da88ca0e6/f5a65bef-756e-4e93-83ee-d47e176976a8.png)

#### 介绍
无侵入的Swagger3/OpenApi3.0接口文档查看工具UI。引用即可生效，无需自己配置路由，无需自己部署swagger-ui到public目录。
插件为你做好了一切，基于ThinkPHP6的无侵入OpenApi UI界面。

#### 软件架构
基于ThinPHP6的ServiceProvider

### 写一个文档
在一个控制器比如appext/demo-blog/controller/User.php里写一个标准的接开文档如下

```

/**
 * 用户控制器
 * @OA\Info(title="用户控制器", version="1.0")
 *
 * @author jry <ijry@qq.com>
 */
class User extends BaseController
{
    /**
     * 用户登录
     * 
     * @OA\POST(
     *     tags={"核心模块"},
     *     summary="用户登录",
     *     description="支持账号密码、手机号、邮箱登录",
     *     path="/core/user/login",
     *     @OA\Response(response="200",description="获取成功"),
     *     @OA\Parameter(
     *       name="account",in="query",required=true,description="用户名",
     *       @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *       name="password",in="query",required=true,description="用户密码",
     *       @OA\Schema(type="string")
     *     )
     * )
     *
     * @param  \think\Request  $request
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function login(Request $request)
    {
    }
}
```

## 注解文档

[https://zircote.github.io/swagger-php/](https://zircote.github.io/swagger-php/)


#### 使用说明

访问 {域名:端口}/apidoc




