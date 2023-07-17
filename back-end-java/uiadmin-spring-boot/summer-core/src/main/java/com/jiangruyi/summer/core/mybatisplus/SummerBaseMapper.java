package com.jiangruyi.summer.core.mapper;

import org.apache.ibatis.annotations.Mapper;
import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import icu.mhb.mybatisplus.plugln.base.mapper.JoinBaseMapper;
import java.util.Collection;
/**
 * 扩展通用 Mapper，支持数据批量插入等功能
 *
 * @author jry
 */
public interface SummerBaseMapper<T> extends JoinBaseMapper<T> {
    /**
     * 批量插入 (mysql)
     *
     * @param entityList 实体列表
     * @return 影响行数
     */
    Integer insertBatchSomeColumn(Collection<T> entityList);
}

