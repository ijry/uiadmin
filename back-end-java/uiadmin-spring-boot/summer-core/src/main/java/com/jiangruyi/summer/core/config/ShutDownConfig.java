package com.jiangruyi.summer.core.config;

import javax.annotation.PreDestroy;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;

@Configuration
public class ShutDownConfig {
    public class TerminateBean {
        @PreDestroy
        public void preDestroy() {
            // 关闭应用会触发
            System.out.println("TerminalBean is destroyed");
        }
    }

    @Bean
    public TerminateBean getTerminateBean() {
        return new TerminateBean();
    }
}
