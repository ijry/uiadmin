package com.jiangruyi.summer.core.annotation;

import io.github.classgraph.*;

import java.lang.annotation.Annotation;
import java.util.*;

/**
 * @Description 注解类工具
 * @create 2022/12/04 21:56
 */
public class AnnotationUtil {

    /**
     * 扫描指定方法注解
     * 
     * @param pkg        扫描包
     * @param annotation 获取的注解类型
     * @return 返回注解参数 [{name:name,value:value}]
     */
    public static List<AnnotationParameterValueList> methodAnnotionScan(String pkg, Class annotation) {
        try (ScanResult scanResult = // Assign scanResult in try-with-resources
            new ClassGraph() // Create a new ClassGraph instance
                .enableAllInfo() // Scan classes, methods, fields, annotations
                .acceptPackages(pkg) // Scan com.xyz and subpackages
                .scan()) { // Perform the scan and return a ScanResult
            // 获取类里指定方法注解
            ClassInfoList ciList = scanResult.getClassesWithMethodAnnotation(annotation);
            List list = new ArrayList();
            for (ClassInfo ci : ciList) {
                for (MethodInfo mli : ci.getMethodInfo()) {
                    for (AnnotationInfo me : mli.getAnnotationInfoRepeatable(annotation)) {
                            list.add(me.getParameterValues());
                    }
                }
            }
            return list;
        }
    }
}
