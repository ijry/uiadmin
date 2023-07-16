package com.jiangruyi.summer.core.config;

import org.springframework.boot.CommandLineRunner;
import org.springframework.stereotype.Component;
import org.springframework.beans.factory.annotation.Value;

@Component
public class OpenBrowser implements CommandLineRunner {

    @Value("${server.port}")
    private String port;

    @Value("${spring.profiles.active}")
    private String envActive;

    @Override
    public void run(String... args) throws Exception {
        
        System.out.println("\n" +
			"                                        \n" +
            "---__----------_--_---_--_----__---)__  \n" +
            "  (_ ` /   /  / /  ) / /  ) /___) /   ) \n" +
            "_(__)_(___(__/_/__/_/_/__/_(___ _/      \n"
		);
		// System.out.println("uiadmin-summer版启动成功");
        try {
            String osName = System.getProperty("os.name");
            String cmd = "";
            switch (osName) {
                case "Mac OS X":
                    cmd = "open";
                    break;
                case "Windows":
                    cmd = "cmd   /c   start";
                    break;
                case "Linux":
                    cmd = "xdg-open";
                default:
                    break;
            }
            if (envActive.equals("dev")) {
                // System.out.println("开始自打开管理后台页面...");
                // Runtime.getRuntime().exec(cmd + "  http://localhost:"+ port + "/xyadmin/");
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }
    }

}