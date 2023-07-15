package com.jiangruyi.summer.boot;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

@SpringBootApplication
@ComponentScan("com.jiangruyi.summer.core") // 扫描核心模块的接口
public class SummerBootApplication {

	public static void main(String[] args) {
		SpringApplication.run(SummerBootApplication.class, args);
	}

}
