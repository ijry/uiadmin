package com.jiangruyi.summer.boot;

import org.apache.commons.lang3.StringUtils;
import org.springframework.beans.factory.annotation.Autowired;
// import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.ModelAndView;

import java.util.*;
import static java.util.stream.Collectors.*;
import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONArray;
import com.alibaba.fastjson.JSONObject;
import com.baomidou.mybatisplus.core.conditions.query.LambdaQueryWrapper;
import com.baomidou.mybatisplus.core.conditions.query.QueryWrapper;
import com.baomidou.mybatisplus.core.metadata.IPage;
import com.baomidou.mybatisplus.core.toolkit.Wrappers;
import com.baomidou.mybatisplus.extension.plugins.pagination.Page;
import com.jiangruyi.summer.core.util.ApiReturnObject;
import com.jiangruyi.summer.core.util.ApiReturnUtil;
import com.jiangruyi.summer.core.util.Tree;
import com.jiangruyi.summer.core.util.xybuilder.XyBuilderForm;
import com.jiangruyi.summer.core.util.xybuilder.XyBuilderList;
import com.jiangruyi.summer.core.annotation.MenuItem;
import jakarta.servlet.http.HttpServletRequest;

import io.swagger.v3.oas.annotations.*;
import io.swagger.v3.oas.annotations.tags.Tag;

/**
 * Demo Admin Controller 后台管理控制器
 *
 * 作者：Auto Generator By 'summer'
 * 生成日期：2022-12-19 16:20:57
 */
@Tag(name = "后台-DEMO示例", description = "")
@RestController("DemoAdminController")
@RequestMapping("/api")
public class DemoController {
    // @Autowired
    // private IProjectService projectService;

    // @Autowired
    // private IUserService userService;

    /**
	 * Demo列表
	 */
    @Operation(summary = "Demo列表")
    @MenuItem(title = "Demo列表", path = "/demo/lists", pmenu = "/content", menuType = 1,
        routeType = "list", apiSuffix = "", apiParams = "", apiMethod = "GET", sortnum = 0)
	@GetMapping("/v1/admin/demo/lists")
    // @PreAuthorize("hasAnyAuthority('ROLE_SUPER_ADMIN', '/v1/admin/demo/lists')")
	public ApiReturnObject lists(HttpServletRequest request) {
        // 查询条件
        QueryWrapper<Object> queryWrapper = new QueryWrapper<>();
        // queryWrapper.orderByDesc("id");

        // 多条件筛选      
        String title = request.getParameter("title");
        if (!StringUtils.isBlank(title)) {
            queryWrapper.eq("title", title);
        }          
        
        // 获取前台发送过来的分页数据
        Integer pageNo = 1;
        if (request.getParameter("page") != null) {
            pageNo = Integer.valueOf(request.getParameter("page"));
        }
        Integer pageSize = 10;
        if (request.getParameter("limit") != null) {
            pageSize = Integer.valueOf(request.getParameter("limit"));
        }
        // Page<Project> page = new Page<Project>(pageNo, pageSize);

        // 查询列表
		// IPage<Project> dataPage = projectService.page(page,queryWrapper);
        // 格式转换
        // JSONArray js = (JSONArray) JSON.toJSON(dataPage.getRecords());

        JSONArray js = new JSONArray();

        // 使用Builder生成列表页面
        XyBuilderList listBuilder = new XyBuilderList();
        listBuilder.addTopButton("add", "新增", new HashMap<String, Object>() {{
            put("title", "新增");
            put("pageType", "drawer");
            put("modalType", "form");
            put("api", "/v1/admin/demo/add");
            put("apiSuffix", new ArrayList());
            put("querySuffix", new ArrayList() {{
            }});
            put("width", "1000px");
        }});
        // 多条件筛选
        listBuilder.addFilterItem("title", "标题", "text", title, new HashMap<String, Object>() {{
        }});        
        listBuilder.addColumn("title", "标题", new HashMap<String, Object>() {{   
            put("type", "");  
            put("width", "120");       
        }});        
        listBuilder.addColumn("cover", "封面", new HashMap<String, Object>() {{
            put("type", "image");
        }});        
        listBuilder.addColumn("status", "状态", new HashMap<String, Object>() {{            
            put("type", "tag");
            put("options", new ArrayList(){{
                add(new HashMap(){{
                    put("title", "禁用");
                    put("value", 0);
                    put("type", "danger");
                }});
                add(new HashMap(){{
                    put("title", "启用");
                    put("value", 1);
                    put("type", "success");
                }});
            }});
        }});        
        listBuilder.addColumn("rightButtonList", "操作", new HashMap<String, Object>() {{
            put("type", "rightButtonList");
            put("minWidth", "120px");
        }});
        listBuilder.addRightButton("edit", "修改", new HashMap<String, Object>() {{
            put("modalType", "form");
            put("pageType", "drawer");
            put("api", "/v1/admin/demo/edit");
            put("width", "1000px");
            put("apiSuffix", new ArrayList(){{ 
                add("id");
            }});
            put("title", "修改迭代需求");
        }}, new HashMap<String, Object>() {{
            put("type", "warning");
        }});
        listBuilder.addRightButton("delete", "删除", new HashMap<String, Object>() {{
            put("title", "确认要删除该记录吗？");
            put("pageType", "modal");
            put("modalType", "confirm");
            put("okText", "确认删除");
            put("cancelText", "取消操作");
            put("content", "确认要删除该记录吗？");
            put("api", "/v1/admin/demo/delete");
            put("apiSuffix", new ArrayList(){{
                add("id");
            }});
            put("querySuffix", new ArrayList() {{
            }});
            put("width", "500px");
        }}, new HashMap<String, Object>() {{
            put("type", "danger");
        }});
        listBuilder.setDataList(js);
        // listBuilder.setTableName("project");
        // listBuilder.setDataPage(dataPage.getTotal(), dataPage.getCurrent(), dataPage.getSize());
        HashMap listData = listBuilder.getData();

        // 添加一层listData
        JSONObject result = new JSONObject();
        result.put("listData", listData);

		return ApiReturnUtil.success(result);
    }

    /**
     * 新增表单
     */
    @Operation(hidden = true, summary = "Demo新增表单")
    @MenuItem(title = "Demo新增表单", path = "/demo/add", pmenu = "/demo/lists", menuType = 2,
        routeType = "form", apiSuffix = "", apiParams = "", apiMethod = "GET", sortnum = 0)
    @GetMapping(path = "/v1/admin/demo/add")
    public ApiReturnObject add(HttpServletRequest request, @RequestBody(required = false) JSONObject bodyData) {
        // 使用Builder生成列表页面
        XyBuilderForm formBuilder = new XyBuilderForm();  
        formBuilder.addFormItem("title", "标题", "text", "", new HashMap<String, Object>() {{            
        }});
        formBuilder.addFormItem("content", "内容", "html", "", new HashMap<String, Object>() {{            
        }});
        formBuilder.setConfig("submitApi", "/v1/admin/demo/doAdd");
        Map<String, Object> formData = formBuilder.getData();

        // 添加一层formData
        JSONObject result = new JSONObject();
        result.put("formData", formData);

        return ApiReturnUtil.success(result);
    }

    /**
     * 新增
     */
    @Operation(summary = "Demo新增", description="新增一条Demo数据")
    @MenuItem(title = "Demo新增", path = "/demo/doAdd", pmenu = "/demo/lists", menuType = 2,
        routeType = "form", apiSuffix = "", apiParams = "", apiMethod = "POST", sortnum = 0)
    @PostMapping(path = "/v1/admin/demo/doAdd")
    // @PreAuthorize("hasAnyAuthority('ROLE_SUPER_ADMIN', '/v1/admin/demo/doAdd')")
    public ApiReturnObject doAdd(HttpServletRequest request, @RequestBody(required = false) JSONObject bodyData) {
        // if("POST".equals(request.getMethod())){
            // String userId = SecurityUtil.getLoginId();
            // Date today = new Date();
            // bodyData.setCreateTime(today);
            // bodyData.setUpdateTime(today);
            // bodyData.setStatus(1);
            // if (!projectService.save(bodyData)) {
            //     return ApiReturnUtil.error(0, "添加出错");
            // }
            return ApiReturnUtil.success("添加成功");
        // }
    }

    /**
     * 编辑表单
     */
    @Operation(hidden = true, summary = "Demo编辑表单")
    @MenuItem(title = "Demo编辑表单", path = "/demo/edit", pmenu = "/demo/lists", menuType = 2,
        routeType = "form", apiSuffix = "", apiParams = "", apiMethod = "GET", sortnum = 0)
    @GetMapping(path = "/v1/admin/demo/edit/{id}")
    public ApiReturnObject edit(HttpServletRequest request, @PathVariable String id) {
        // 获取记录
        // Project info = projectService.getById(id);

        // 使用Builder生成页面
        XyBuilderForm formBuilder = new XyBuilderForm();
        formBuilder.addFormItem("id", "id", "text", "", new HashMap<String, Object>() {{
            put("disabled", true);       
        }});   
        formBuilder.addFormItem("title", "标题", "text", "", new HashMap<String, Object>() {{            
        }});
        formBuilder.addFormItem("content", "内容", "html", "", new HashMap<String, Object>() {{            
        }});   
        formBuilder.addFormItem("status", "状态", "radio", "", new HashMap<String, Object>() {{            
            put("options", new ArrayList<Map>(){{
                add(new HashMap<String, Object>(){{
                    put("title", "禁用");
                    put("value", 0);
                    put("type", "danger");
                }});
                add(new HashMap<String, Object>(){{
                    put("title", "启用");
                    put("value", 1);
                    put("type", "success");
                }});
            }});
        }});
        // formBuilder.setFormValues(info);
        formBuilder.setFormMethod("put");
        formBuilder.setConfig("submitApi", "/v1/admin/demo/doEdit/" + id);
        Map<String, Object> formData = formBuilder.getData();

        // 添加一层formData
        JSONObject result = new JSONObject();
        result.put("formData", formData);

        return ApiReturnUtil.success(result);
    }

    /**
     * 编辑
     */
    @Operation(summary = "Demo编辑", description="修改Demo数据")
    @MenuItem(title = "Demo修改", path = "/demo/doEdit", pmenu = "/demo/lists", menuType = 2,
        routeType = "form", apiSuffix = "", apiParams = "", apiMethod = "PUT", sortnum = 0)
    @PutMapping(path = "/v1/admin/demo/doEdit/{id}")
    // @PreAuthorize("hasAnyAuthority('ROLE_SUPER_ADMIN', '/v1/admin/demo/doEdit')")
    public ApiReturnObject doEdit(HttpServletRequest request, @PathVariable String id, @RequestBody(required = false) JSONObject bodyData) {
        // if("PUT".equals(request.getMethod())){
            // String userId = SecurityUtil.getLoginId();
            // Date today = new Date();
            // bodyData.setUpdateTime(today);
            // if (!projectService.updateById(bodyData)) {
            //     return ApiReturnUtil.error(0, "修改出错");
            // }
            return ApiReturnUtil.success("修改成功");
        // }
    }

    /**
	 * 删除
	 */
    @Operation(summary = "Demo删除", description="删除一个Demo")
    @MenuItem(title = "Demo删除", path = "/demo/delete", pmenu = "/demo/lists", menuType = 2,
        routeType = "confirm", apiSuffix = "/:id", apiParams = "", apiMethod = "DELETE", sortnum = 0)
    @DeleteMapping("/v1/admin/demo/delete/{id}")
    // @PreAuthorize("hasAnyAuthority('ROLE_SUPER_ADMIN', '/v1/admin/demo/delete')")
    public ApiReturnObject delete(HttpServletRequest request, @PathVariable String id,
        @RequestBody(required = false) Map<String, Object> postData) {
        return ApiReturnUtil.success("删除成功");
    }
}

