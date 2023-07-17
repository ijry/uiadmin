package com.jiangruyi.summer.core.mybatisplus;

import com.baomidou.mybatisplus.extension.handlers.JacksonTypeHandler;
import com.fasterxml.jackson.core.type.TypeReference;

import java.io.IOException;
import java.util.List;
import java.util.Map;

/**
 * String数组类型处理器<br/>
 * 使用方法: <br/>
 * @TableField(typeHandler = ListTypeStringHandler.class)<br/>
 * private List<HashMap> fieldName;  <br/>
 * 不要问我为什么要重写 parse 因为顶层父类是无法获取到准确的待转换复杂返回类型数据
 */
public class ListTypeStringHandler extends JacksonTypeHandler {

    public ListTypeStringHandler(Class<?> type) {
        super(type);
    }

    @Override
    protected Object parse(String json) {
        try {
            return getObjectMapper().readValue(json, new TypeReference<List<String>>() {
            });
        } catch (IOException e) {
            throw new RuntimeException(e);
        }
    }
}
