package com.jiangruyi.summer.boot;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.autoconfigure.jdbc.DataSourceAutoConfiguration;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.web.bind.annotation.GetMapping;

@SpringBootApplication(exclude= {DataSourceAutoConfiguration.class})
@ComponentScan("com.jiangruyi.summer.*") // 扫描核心模块的接口
public class SummerBootApplication {

	public static void main(String[] args) {
		SpringApplication.run(SummerBootApplication.class, args);
	}

	@GetMapping("/")
	public String home() {
		return "<a href='/xyadmin/'>打开后台</a>";
	}
}
