package com.jiangruyi.summer.core.util.excel;

import java.io.File;
import java.io.InputStream;
import java.util.List;
import java.util.function.Consumer;

import com.alibaba.excel.EasyExcel;
import com.alibaba.excel.read.builder.ExcelReaderBuilder;

/**
 * Excel工具类.
 * 来自https://zhuanlan.zhihu.com/p/96417053
 * @author www@yiynx.cn
 */
public class ExcelUtil extends EasyExcel {
  private ExcelUtil() {}
  
  public static <T> ExcelReaderBuilder read(String pathName, Class<T> head, Integer pageSize, Consumer<List<T>> consumer) {
    return read(pathName, head, new EasyExcelConsumerListener<>(pageSize, consumer));
  }

  public static <T> ExcelReaderBuilder read(File file, Class<T> head, Integer pageSize, Consumer<List<T>> consumer) {
    return read(file, head, new EasyExcelConsumerListener<>(pageSize, consumer));
  }
  
  public static <T> ExcelReaderBuilder read(InputStream inputStream, Class<T> head, Integer pageSize, Consumer<List<T>> consumer) {
    return read(inputStream, head, new EasyExcelConsumerListener<>(pageSize, consumer));
  }
}