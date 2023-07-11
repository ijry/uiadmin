## 简洁

扩展管理模块主要用于管理uiadmin支持的扩展，它们一般在extention目录下

## 流程

### 安装一个扩展

1.从官方插件市场下载一个扩展
2.写入xy_ext表
3.拷贝extention/插件名称/src/database下的数据库迁移文件到/database下
4.执行php think migrate:run
4.执行php think seed:run

### 卸载一个扩展

1.从xy-ext表删除记录
2.为了安全不对数据库做操作，如需彻底卸载需要用户手动删除数据表

