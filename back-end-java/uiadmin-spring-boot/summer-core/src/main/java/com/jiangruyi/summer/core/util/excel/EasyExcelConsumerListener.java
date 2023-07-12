package com.jiangruyi.summer.core.util.excel;

import java.util.ArrayList;
import java.util.List;
import java.util.function.Consumer;

import com.alibaba.excel.context.AnalysisContext;
import com.alibaba.excel.event.AnalysisEventListener;

/**
 * EasyExcel消费监听.
 * 来自https://zhuanlan.zhihu.com/p/96417053
 * @author www@yiynx.cn
 * @param <T>
 * see <a href="https://github.com/alibaba/easyexcel/blob/master/src/test/java/com/alibaba/easyexcel/test/demo/read/DemoDataListener.java">DemoDataListener</a>
 */
public class EasyExcelConsumerListener<T> extends AnalysisEventListener<T> {
  private int pageSize;
  private List<T> list;
  private Consumer<List<T>> consumer;
  
  public EasyExcelConsumerListener(int pageSize, Consumer<List<T>> consumer) {
      this.pageSize = pageSize;
      this.consumer = consumer;
      list = new ArrayList<>(pageSize);
  }
  
  @Override
  public void invoke(T data, AnalysisContext context) {
      list.add(data);
      if (list.size() >= pageSize) {
          consumer.accept(list);
          list.clear();
      }
  }
  
  @Override
  public void doAfterAllAnalysed(AnalysisContext context) {
      consumer.accept(list);
  }
}
