package com.jiangruyi.summer.core.controller;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.HashMap;

import javax.annotation.Resource;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.alibaba.fastjson.JSONObject;
import com.jiangruyi.summer.core.util.ApiReturnUtil;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.core.env.Environment;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.context.WebApplicationContext;

import com.jiangruyi.summer.core.annotation.MenuItem;

/**
 * @author Jry
 */
@Controller
@RestController
@RequestMapping("/")
public class CoreController {
    @Autowired
    WebApplicationContext applicationContext;

    /**
	 * 调用云后台
	 * @throws IOException 
	 * @throws ClientProtocolException 
	 */
	@GetMapping("/xyadmin")
    public void index(HttpServletResponse response) throws ClientProtocolException, IOException {
        response.sendRedirect("/xyadmin/");
    }

	/**
	 * 调用云后台
	 * @throws IOException 
	 * @throws ClientProtocolException 
	 */
	@GetMapping("/xyadmin/")
	public String admin() throws ClientProtocolException, IOException {
		// (1) 创建HttpGet实例  
		HttpGet get = new HttpGet("https://uiadmin.net/xyadmin/?version=1.2.0");  
		  
		// (2) 使用HttpClient发送get请求，获得返回结果HttpResponse  
		HttpClient http = new DefaultHttpClient();  
		HttpResponse response = http.execute(get);  
		  
		// (3) 读取返回结果  
		if (response.getStatusLine().getStatusCode() == 200) {  
		    HttpEntity entity = response.getEntity();
		    try {
		    	InputStream in = entity.getContent();
		    	return this.inputStreamToString(in);
		    } catch (IOException e) {
	        	// log.info("e");
	        }
		    
		}
		return "";
	}
    
    /**
	 * inputStreamToString
	 */
	private String inputStreamToString(InputStream is) {
        String line = "";
        StringBuilder total = new StringBuilder();
        // Wrap a BufferedReader around the InputStream
        BufferedReader rd = new BufferedReader(new InputStreamReader(is));
        try {
            // Read response until the end
            while ((line = rd.readLine()) != null) {
                total.append(line);
            }
        } catch (IOException e) {
        	// log.info("e");
        }
        // Return full string
        return total.toString();
    }

    @Resource
	private Environment environment;

	/**
	 * SpringBoot版云后台对接接口
	 * 实现这个接口里的几个标记必须实现的API就可以快速跨语言对接XYAdmin云后台。
	 * XYAdmin是一个基于Vue、跨语言的、统一接口、全自动Builder渲染页面的云后台，不限制后端语言，可以以一套相同的API自由切换后端语言。
	 */
	@GetMapping("/xyadmin/api")
	public Object api(HttpServletRequest request) {
        String contextPath = environment.getProperty("server.servlet.context-path");
        if (contextPath == null) {
            contextPath = "";
        }
		final String apiBase = request.getScheme() + "://"
            + request.getServerName() + ":"
            + request.getLocalPort()
            + contextPath
            + "/api";
		HashMap<String, Object> data = new HashMap<String, Object>() {
			{
				put("lang", "java");
				put("framework", "spring-boot");
				put("name", "summer");
				put("title", environment.getProperty("summer.site.title")); // 网站名称
				put("stype", "应用");
				put("version", "1.2.0");
				put("domainRoot", request.getScheme()+"://"+ request.getServerName() + ":" + request.getLocalPort());
				put("api", new HashMap<String, Object>() {
					{
						put("apiBase", apiBase);  // 必须实现
						put("apiLogin", "/v1/admin/user/login"); // 必须实现
						put("apiAdmin", "/v1/admin/index/index");
						put("apiMenuTrees", "/v1/admin/menu/trees"); // 必须实现
						put("apiConfig", "/v1/site/info"); // 此接口注意不要返回isClassified=1的字段
						put("apiUserInfo", "/v1/admin/user/info");
					}
				});
                put("config", new HashMap<String, Object>() {
                    {
                        put("useVerify", environment.getProperty("summer.user.useVerify")); // 开启登录验证码
                    }
                });
				put("siteInfo", new HashMap<String, Object>() {
                    {
                        put("title", environment.getProperty("summer.site.title"));
                    }
                });
			}
		};

		return ApiReturnUtil.success(data);
    }

    /**
	 * 后台首页
	 */
    @MenuItem(title = "系统", path = "/_system", pmenu = "/default_root", menuType = -1, sortnum = 99, icon="xyicon-settings")
    @MenuItem(title = "开发工具", path = "/dev", pmenu = "/_system", menuType = 0, sortnum = 0)
    @MenuItem(title = "内容", path = "/_content", pmenu = "/default_root", menuType = -1, sortnum = 10, icon="xyicon-plane")
    @MenuItem(title = "内容管理", path = "/content", pmenu = "/_content", menuType = 0, sortnum = 0)
	@GetMapping("/api/v1/admin/index/index")
	public Object adminIndex() {
        ArrayList dataList = new ArrayList();
        dataList.add(new HashMap<String, Object>() {
            {
                put("span", 24);
                put("type", "count");
                put("content", new ArrayList() {
                    {
                        add(new HashMap<String, Object>() {
                            {
                                put("item", new HashMap<String, Object>() {
                                    {
                                        put("icon", "ivu-icon ivu-icon-md-contacts");
                                        put("bgColor", "#2db7f5");
                                        put("title", "");
                                    }
                                });
                                put("current", new HashMap<String, Object>() {
                                    {
                                        put("value", "0");
                                        put("suffix", "");
                                    }
                                });
                                put("content", new HashMap<String, Object>() {
                                    {
                                        put("value", "注册用户");
                                    }
                                });
                            }
                        });
                        add(new HashMap<String, Object>() {
                            {
                                put("item", new HashMap<String, Object>() {
                                    {
                                        put("icon", "ivu-icon ivu-icon-md-person-add");
                                        put("bgColor", "#19be6b");
                                        put("title", "");
                                    }
                                });
                                put("current", new HashMap<String, Object>() {
                                    {
                                        put("value", "0");
                                        put("suffix", "");
                                    }
                                });
                                put("content", new HashMap<String, Object>() {
                                    {
                                        put("value", "今日新增");
                                    }
                                });
                            }
                        });
                        add(new HashMap<String, Object>() {
                            {
                                put("item", new HashMap<String, Object>() {
                                    {
                                        put("icon", "ivu-icon ivu-icon-md-clock");
                                        put("bgColor", "#ff9900");
                                        put("title", "");
                                    }
                                });
                                put("current", new HashMap<String, Object>() {
                                    {
                                        put("value", "0");
                                        put("suffix", "");
                                    }
                                });
                                put("content", new HashMap<String, Object>() {
                                    {
                                        put("value", "总消费");
                                    }
                                });
                            }
                        });
                        add(new HashMap<String, Object>() {
                            {
                                put("item", new HashMap<String, Object>() {
                                    {
                                        put("icon", "ivu-icon ivu-icon-ios-paper-plane");
                                        put("bgColor", "#ed4014");
                                        put("title", "");
                                    }
                                });
                                put("current", new HashMap<String, Object>() {
                                    {
                                        put("value", "0");
                                        put("suffix", "");
                                    }
                                });
                                put("content", new HashMap<String, Object>() {
                                    {
                                        put("value", "今日消费");
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
        dataList.add(new HashMap<String, Object>() {
            {
                put("span", 12);
                put("type", "card");
                put("title", "系统信息");
                put("content", new ArrayList() {
                    {
                        add(new HashMap<String, Object>() {
                            {
                                put("type", "text");
                                put("title", "服务器IP");
                                put("value", "");
                            }
                        });
                        add(new HashMap<String, Object>() {
                            {
                                put("type", "text");
                                put("title", "WEB服务器");
                                put("value", "");
                            }
                        });
                        add(new HashMap<String, Object>() {
                            {
                                put("type", "text");
                                put("title", "JDK版本");
                                put("value", "");
                            }
                        });
                        add(new HashMap<String, Object>() {
                            {
                                put("type", "text");
                                put("title", "服务器时间");
                                put("value", "");
                            }
                        });
                        add(new HashMap<String, Object>() {
                            {
                                put("type", "text");
                                put("title", "官方网站");
                                put("value", "https://jiangruyi.com(ijry@qq.com)");
                            }
                        });
                    }
                });
            }
        });
        dataList.add(new HashMap<String, Object>() {
            {
                put("span", 12);
                put("type", "card");
                put("title", "项目信息");
                put("content", new ArrayList() {
                    {
                        add(new HashMap<String, Object>() {
                            {
                                put("type", "text");
                                put("title", "项目名称");
                                put("value", "");
                            }
                        });
                        add(new HashMap<String, Object>() {
                            {
                                put("type", "text");
                                put("title", "项目口号");
                                put("value", "");
                            }
                        });
                        add(new HashMap<String, Object>() {
                            {
                                put("type", "text");
                                put("title", "项目简介");
                                put("value", "");
                            }
                        });
                        add(new HashMap<String, Object>() {
                            {
                                put("type", "text");
                                put("title", "ICP备案号");
                                put("value", "");
                            }
                        });
                    }
                });
            }
        });

        JSONObject result = new JSONObject();
        result.put("dataList", dataList);
		return ApiReturnUtil.success(result);
    }

    /**
	 * 获取网站配置信息
	 */
	@GetMapping("/api/v1/site/info")
	public Object sysConfig() {
        JSONObject result = new JSONObject();
        result.put("title", environment.getProperty("summer.site.title"));
        result.put("logo", environment.getProperty("summer.site.logo"));
        result.put("logoTitle", environment.getProperty("summer.site.logoTitle"));
		return ApiReturnUtil.success(result);
	}
}
