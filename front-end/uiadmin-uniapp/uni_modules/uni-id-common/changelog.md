## 1.0.18（2024-07-08）
- checkToken时如果传入的token为空则返回uni-id-check-token-failed错误码以便uniIdRouter能正常跳转
## 1.0.17（2024-04-26）
- 兼容uni-app-x对客户端uniPlatform的调整（uni-app-x内uniPlatform区分app-android、app-ios）
## 1.0.16（2023-04-25）
- 新增maxTokenLength配置，用于限制数据库用户记录token数组的最大长度
## 1.0.15（2023-04-06）
- 修复部分语言国际化出错的Bug
## 1.0.14（2023-03-07）
- 修复 admin用户包含其他角色时未包含在token的Bug
## 1.0.13（2022-07-21）
- 修复 创建token时未传角色权限信息生成的token不正确的bug
## 1.0.12（2022-07-15）
- 提升与旧版本uni-id的兼容性（补充读取配置文件时回退平台app-plus、h5），但是仍推荐使用新平台名进行配置（app、web）
## 1.0.11（2022-07-14）
- 修复 部分情况下报`read property 'reduce' of undefined`的错误
## 1.0.10（2022-07-11）
- 将token存储在用户表的token字段内，与旧版本uni-id保持一致
## 1.0.9（2022-07-01）
- checkToken兼容token内未缓存角色权限的情况，此时将查库获取角色权限
## 1.0.8（2022-07-01）
- 修复clientDB默认依赖时部分情况下获取不到uni-id配置的Bug
## 1.0.7（2022-06-30）
- 修复config文件不合法时未抛出具体错误的Bug
## 1.0.6（2022-06-28）
- 移除插件内的数据表schema
## 1.0.5（2022-06-27）
- 修复使用多应用配置时报`Cannot read property 'appId' of undefined`的Bug
## 1.0.4（2022-06-27）
- 修复使用自定义token内容功能报错的Bug [详情](https://ask.dcloud.net.cn/question/147945)
## 1.0.2（2022-06-23）
- 对齐旧版本uni-id默认配置
## 1.0.1（2022-06-22）
- 补充对uni-config-center的依赖
## 1.0.0（2022-06-21）
- 提供uni-id token创建、校验、刷新接口，简化旧版uni-id公共模块
