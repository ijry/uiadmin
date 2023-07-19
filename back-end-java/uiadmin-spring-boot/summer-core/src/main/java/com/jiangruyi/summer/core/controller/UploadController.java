package com.jiangruyi.summer.core.controller;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.util.UUID;

import com.alibaba.fastjson.JSONObject;
import com.jiangruyi.summer.core.util.ApiReturnUtil;
import com.jiangruyi.summer.core.util.ApiReturnObject;

import org.springframework.boot.system.ApplicationHome;
import org.springframework.stereotype.Controller;
import org.springframework.util.ResourceUtils;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.multipart.MultipartFile;

import io.swagger.v3.oas.annotations.*;
import io.swagger.v3.oas.annotations.tags.Tag;

/**
 * 上传
 */
@Controller("CoreUploadController")
@RestController
public class UploadController {
    /**
	 * 上传接口
	 */
    @PostMapping("/api/v1/upload/upload")
    public ApiReturnObject upload(@RequestParam("file") MultipartFile file) {
        if (file.isEmpty()) {
            return ApiReturnUtil.error(0, "上传失败，请选择文件");
        }
        String originalFileName = file.getOriginalFilename();
        String fileNameExt = originalFileName.substring(originalFileName.lastIndexOf("."));
        String fileName = UUID.randomUUID().toString() + fileNameExt;
        String filePath = "/upload/files/";

        // 获取根目录
        File basePath = getBasePath();
        
        // 如果上传目录为/static/upload/files/，则可以如下获取：
        File upload = new File(basePath.getAbsolutePath(), "static" + filePath);
        if(!upload.exists()) upload.mkdirs();
        System.out.println("upload url:" + upload.getAbsolutePath());
        // 在开发测试模式时，得到的地址为：{项目跟目录}/target/static/upload/
        // 在打包成jar正式发布时，得到的地址为：{发布jar包目录}/static/upload/
        
        File dest = new File(upload.getAbsolutePath(), fileName);
        try {
            file.transferTo(dest);
            JSONObject result = new JSONObject();
            result.put("name", originalFileName);
            result.put("path", filePath + fileName);
            result.put("url", filePath + fileName);
            return ApiReturnUtil.success(result);
        } catch (IOException e) {
            return ApiReturnUtil.error(0, e.getMessage());
        }
    }

    // 获取根目录
    // 注意根目录获取与jar的启动目录有关，应当先cd到jar所在目录启动，这样classpath:才会准确。
    private File getBasePath() {
        File path = null;
        try {
            //获取根目录
            path = new File(ResourceUtils.getURL("classpath:").getPath());
        } catch (Exception e) {
            return null;
        }
        if(!path.exists()) {
            path = new File("");
        }
        return path;
    }
}
