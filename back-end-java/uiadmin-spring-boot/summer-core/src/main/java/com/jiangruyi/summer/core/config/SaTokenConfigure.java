package com.jiangruyi.summer.core.config;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.Primary;

import cn.dev33.satoken.config.SaTokenConfig;

/**
 * sa-token代码方式进行配置
 */
@Configuration
public class SaTokenConfigure {

    // 获取配置Bean (以代码的方式配置sa-token, 此配置会覆盖yml中的配置)
    @Primary
    @Bean(name="SaTokenConfigure")
    public SaTokenConfig getSaTokenConfigPrimary() {
        SaTokenConfig config = new SaTokenConfig();
        config.setTokenName("Authorization");       // token名称 (同时也是cookie名称)
        config.setTimeout(30 * 24 * 60 * 60);       // token有效期，单位s 默认30天
        config.setActivityTimeout(-1);              // token临时有效期 (指定时间内无操作就视为token过期) 单位: 秒
        config.setIsConcurrent(true);               // 是否允许同一账号并发登录 (为true时允许一起登录, 为false时新登录挤掉旧登录) 
        config.setIsShare(true);                    // 在多人登录同一账号时，是否共用一个token (为true时所有登录共用一个token, 为false时每次登录新建一个token) 
        config.setTokenStyle("uuid");               // token风格 
        config.setTokenPrefix("Bearer");
        return config;
    }

}

