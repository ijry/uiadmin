package com.jiangruyi.summer.core.service;

import java.util.List;
import java.util.stream.Collectors;

import org.springframework.stereotype.Service;
import com.baomidou.mybatisplus.extension.service.IService;
import com.baomidou.mybatisplus.core.conditions.query.QueryWrapper;

@Service("CoreBaseService")
public interface IBaseService<T> extends IService<T> {
    /**
     * 根据ID列表获取指定列
     * @param dataIds
     * @param columnName
     * @author jry
     * @return
     */
    default public List<String> selectColumnByIds(List<Integer> dataIds, String columnName) {
        return selectColumnByIds(dataIds, columnName, "id");
    }

    /**
     * 根据ID列表获取指定列
     * @param dataIds
     * @param columnName
     * @param idField
     * @author jry
     * @return
     */
    default public List<String> selectColumnByIds(List<Integer> dataIds, String columnName, String idFleid) {
        QueryWrapper<T> queryWrapper = new QueryWrapper<>();
        queryWrapper.select(columnName)
            .in(idFleid, dataIds);
        return this.getBaseMapper().selectObjs(queryWrapper).stream()
            .map(o -> (String) o)
            .collect(Collectors.toList());
    }
}
