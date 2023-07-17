package com.jiangruyi.summer.core.config;

import org.springdoc.core.models.GroupedOpenApi;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.Primary;
import org.springframework.core.env.Environment;

import java.io.File;

import io.swagger.v3.oas.models.*;
import io.swagger.v3.oas.models.info.Info;
import io.swagger.v3.oas.models.info.License;
import jakarta.annotation.Resource;

/**
 * SpringDoc API文档相关配置
 * Created by jry on 2022/12/09
 */
@Configuration
public class SpringDocConfig {
    @Bean
    public OpenAPI springShopOpenAPI() {
        String version = "1.0.0";
        if (version == null) {
            version = environment.getProperty("summer.system.api-version");
        }
        return new OpenAPI()
                .info(
                    new Info()
                        .title(environment.getProperty("summer.site.title") + "-API接口文档")
                        .description("基于OpenApi规范自动生成的接口文档")
                        .version("v" + version)
                        // .license(new License().name("").url("http://springdoc.org")))
                        // .externalDocs(new ExternalDocumentation()
                        //.url("https://uiadmin.net")
                );
    }

    @Resource
	private Environment environment;

    @Bean
    public GroupedOpenApi adminApi() {
        return GroupedOpenApi.builder()
                .group(environment.getProperty("summer.site.title") + "-管理后台接口")
                .pathsToMatch("/api/v1/admin/**")
                .build();
    }

    @Bean
    public GroupedOpenApi publicApi() {
        return GroupedOpenApi.builder()
                .group(environment.getProperty("summer.site.title") + "-前台接口")
                .pathsToExclude("/api/v1/admin/**")
                .pathsToMatch("/api/v1/**")
                .build();
    }

    @Bean
    public GroupedOpenApi eAdminApi() {
        return GroupedOpenApi.builder()
                .group(environment.getProperty("summer.site.title") + "-商家后台接口")
                .pathsToMatch("/api/v1/eadmin/**")
                .build();
    }
}
