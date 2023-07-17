package com.jiangruyi.summer.core.config;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;

import com.baomidou.mybatisplus.annotation.DbType;
import com.baomidou.mybatisplus.core.injector.AbstractMethod;
import com.baomidou.mybatisplus.core.injector.DefaultSqlInjector;
import com.baomidou.mybatisplus.core.metadata.TableInfo;
import com.baomidou.mybatisplus.autoconfigure.ConfigurationCustomizer;
import com.baomidou.mybatisplus.extension.injector.methods.InsertBatchSomeColumn;
import com.baomidou.mybatisplus.extension.plugins.MybatisPlusInterceptor;
import com.baomidou.mybatisplus.extension.plugins.inner.PaginationInnerInterceptor;

import icu.mhb.mybatisplus.plugln.config.MybatisPlusJoinConfig;
import icu.mhb.mybatisplus.plugln.injector.JoinDefaultSqlInjector;

import java.util.List;

@Configuration
public class MybatisPlusConfig {

    /**
     * MybatisPlusJoin必须不然无法注入
     * EasySqlInjector中已处理这里不再重复需要
     * @param mapperClass
     * @param tableInfo
     * @return
     */
    // @Override
    // public List<AbstractMethod> getMethodList(Class<?> mapperClass, TableInfo tableInfo) {
    //     return super.getMethodList(mapperClass, tableInfo);
    // }

    /**
     * MybatisPlusJoin配置
     */
    @Bean
    public MybatisPlusJoinConfig mybatisPlusJoinConfig() {
        return MybatisPlusJoinConfig.builder()
                // 查询字段别名关键字
                .columnAliasKeyword("as")
                // 表、left join、right join、inner join 表别名关键字
                .tableAliasKeyword("as")
                /*
                  是否使用MappedStatement缓存，如果使用在JoinInterceptor中就会更改
                  MappedStatement的id，导致mybatis-plus-mate 的某些拦截器插件报错，
                  设置成false，代表不使用缓存则不会更改MappedStatement的id
                 */
                .isUseMsCache(false)
                .build();
    }

    /**
     * 新的分页插件,一缓和二缓遵循mybatis的规则,需要设置 MybatisConfiguration#useDeprecatedExecutor = false 避免缓存出现问题(该属性会在旧插件移除后一同移除)
     */
    @Bean
    public MybatisPlusInterceptor mybatisPlusInterceptor() {
        MybatisPlusInterceptor interceptor = new MybatisPlusInterceptor();
        interceptor.addInnerInterceptor(new PaginationInnerInterceptor(DbType.MYSQL));
        return interceptor;
    }

    /**
     * 支持mysql批量插入
     * 需要在自定义BaseMapper中增加insertBatchSomeColumn
     * MybatisPlusJoin必须新加TableInfo不然无法注入
     */
    public class EasySqlInjector extends JoinDefaultSqlInjector {
        @Override
        public List<AbstractMethod> getMethodList(Class<?> mapperClass, TableInfo tableInfo) {
            List<AbstractMethod> methodList = super.getMethodList(mapperClass, tableInfo);
            methodList.add(new InsertBatchSomeColumn());
            return methodList;
        }
    }
    @Bean
    public EasySqlInjector easySqlInjector() {
        return new EasySqlInjector();
    }

    // @Bean
    // public ConfigurationCustomizer configurationCustomizer() {
    //     // return configuration -> configuration.setUseDeprecatedExecutor(false);
    // }
}
