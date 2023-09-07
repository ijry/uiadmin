package com.jiangruyi.summer.generator;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.core.env.Environment;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import java.util.*;
import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONArray;
import com.alibaba.fastjson.JSONObject;
import com.alibaba.fastjson.serializer.SerializerFeature;
import com.baomidou.mybatisplus.core.conditions.query.QueryWrapper;
import com.jiangruyi.summer.core.util.ApiReturnUtil;
import com.jiangruyi.summer.core.util.Tree;
import com.jiangruyi.summer.core.util.xybuilder.XyBuilderForm;
import com.jiangruyi.summer.core.util.xybuilder.XyBuilderList;
import com.jiangruyi.summer.core.annotation.MenuItem;
import com.jiangruyi.summer.generator.CodeGenerator;
import com.jiangruyi.summer.generator.mapper.TableDao;

import javax.annotation.Resource;
import javax.servlet.http.HttpServletRequest;

import io.swagger.v3.oas.annotations.*;

@RestController
@RequestMapping("/")
public class GenAdminController {

    @Autowired
	private Environment environment;

    @Autowired
    TableDao tableDao;

    /**
	 * 代码生成器数据表列表
	 */
    @Operation(hidden = true)
    @MenuItem(title = "代码生成器", path = "/gen/lists", pmenu = "/dev", menuType = 1,
        routeType = "list", apiSuffix = "", apiParams = "", apiMethod = "GET", sortnum = 0)
	@GetMapping("/api/v1/admin/gen/lists")
	public Object lists() {
        List currentModelList = tableDao.listTable();
        JSONArray js = (JSONArray) JSON.toJSON(currentModelList);

        // 使用Builder生成列表页面
        XyBuilderList listBuilder = new XyBuilderList();
        listBuilder.addTopButton("add", "批量生成", new HashMap<String, Object>() {{
            put("title", "批量生成");
            put("pageType", "modal");
            put("modalType", "form");
            put("api", "/v1/admin/gen/genCodeBatch");
            put("apiSuffix", new ArrayList());
            put("querySuffix", new ArrayList() {{
                add(new ArrayList() {{
                    add("tableNames");
                    add("TABLE_NAME");
                }});
            }});
            put("width", "700px");
        }}, new HashMap<String, Object>() {{
            put("type", "danger");
        }});
        listBuilder.addColumn("TABLE_NAME", "表名称", new HashMap<String, Object>() {{
            put("minWidth", "220px");
        }});
        listBuilder.addColumn("TABLE_COMMENT", "表备注", new HashMap<String, Object>() {{
        }});
        listBuilder.addColumn("ENGINE", "存储引擎", new HashMap<String, Object>() {{
        }});
        listBuilder.addColumn("TABLE_COLLATION", "字符集", new HashMap<String, Object>() {{
            put("minWidth", "180px");
        }});
        listBuilder.addColumn("DATA_LENGTH", "数据大小", new HashMap<String, Object>() {{
        }});
        listBuilder.addColumn("AUTO_INCREMENT", "自增", new HashMap<String, Object>() {{
        }});
        listBuilder.addColumn("rightButtonList", "操作", new HashMap<String, Object>() {{
            put("type", "rightButtonList");
            put("minWidth", "160px");
        }});
        listBuilder.addRightButton("genCode", "生成", new HashMap<String, Object>() {{
            put("formType", "form");
            put("api", "/v1/admin/gen/genCode");
            put("width", "1000px");
            put("apiSuffix", new ArrayList(){{ 
            }});
            put("querySuffix", new ArrayList(){{ 
                add(
                    new ArrayList(){{ 
                        add("tableName");
                        add("TABLE_NAME");
                    }}
                );
            }});
            put("title", "生成代码");
        }}, new HashMap<String, Object>() {{
            put("type", "danger");
        }});
        listBuilder.setDataList(js);
        HashMap listData = listBuilder.getData();

        // 添加一层listData
        JSONObject result = new JSONObject();
        result.put("listData", listData);

		return ApiReturnUtil.success(result);
    }

    /**
     * 代码生成
     */
    @Operation(hidden = true)
    @MenuItem(title = "代码生成", path = "/gen/genCode", pmenu = "/gen/lists", menuType = 2,
        routeType = "form", apiSuffix = "", apiParams = "", apiMethod = "GET|POST", sortnum = 0)
    @RequestMapping("/api/v1/admin/gen/genCode")
    public Object genCode(HttpServletRequest request, @RequestBody(required = false) JSONObject bodyData) {
		if("POST".equals(request.getMethod())){
            String[] tables = { bodyData.getString("tableName") };
            String js = JSONObject.toJSONString(bodyData.getJSONArray("genFiles"), SerializerFeature.WriteClassName);
            List<String> genFiles = JSONObject.parseArray(js, String.class);
            for (String table : tables) {
                CodeGenerator gen = new CodeGenerator(table,
                    bodyData.getString("targetPath"),
                    bodyData.getString("pmenu"),
                    genFiles);
                gen.setDb(environment.getProperty("spring.datasource.url"),
                    environment.getProperty("spring.datasource.username"),
                    environment.getProperty("spring.datasource.password"));
                String msg = gen.create();
                System.out.println(msg);
            }
            return ApiReturnUtil.success("生成完成");
		} else {
            // 使用Builder生成列表页面
            XyBuilderForm formBuilder = new XyBuilderForm();
            formBuilder.addFormItem("tableName", "表名称", "text", "",new HashMap<String, Object>() {{
                put("disabled", true);
            }});
            formBuilder.addFormItem("targetPath", "生成路径", "text", "",new HashMap<String, Object>() {{
                put("tip", "生成路径类似com/xxx/xxx/xxx");
            }});
            formBuilder.addFormItem("pmenu", "菜单节点", "text", "",new HashMap<String, Object>() {{
                put("tip", "默认/content");
            }});
            formBuilder.addFormItem("genFiles", "生成文件", "checkbox", "",new HashMap<String, Object>() {{
                put("options", new ArrayList(){{
                    add(new HashMap<String, String>(){{
                        put("title", "Entity");
                        put("value", "Entity");
                    }});
                    add(new HashMap<String, String>(){{
                        put("title", "AdminController");
                        put("value", "AdminController");
                    }});
                    add(new HashMap<String, String>(){{
                        put("title", "Contriller");
                        put("value", "Contriller");
                    }});
                    add(new HashMap<String, String>(){{
                        put("title", "Service");
                        put("value", "Service");
                    }});
                    add(new HashMap<String, String>(){{
                        put("title", "Mapper");
                        put("value", "Mapper");
                    }});
                }});
            }});
            formBuilder.setFormValues(new HashMap<String, Object>() {{
                put("tableName", request.getParameter("tableName"));
                put("targetPath", "com/jiangruyi/summer/gen");
                put("pmenu", "/content");
                put("genFiles", new ArrayList());
            }});
            formBuilder.setConfig("submitApi", "/v1/admin/gen/genCode");
            Map<String, Object> formData = formBuilder.getData();

            // 添加一层formData
            JSONObject result = new JSONObject();
            result.put("formData", formData);

            return ApiReturnUtil.success(result);
        }
    }

    /**
     * 代码批量生成
     */
    @Operation(hidden = true)
    @MenuItem(title = "代码批量生成", path = "/gen/genCodeBatch", pmenu = "/gen/lists", menuType = 2,
        routeType = "form", apiSuffix = "", apiParams = "", apiMethod = "GET|POST", sortnum = 0)
    @RequestMapping("/api/v1/admin/gen/genCodeBatch")
    public Object genCodeBatch(HttpServletRequest request, @RequestBody(required = false) JSONObject bodyData) {
		if("POST".equals(request.getMethod())){
            List<Map> tables = tableDao.listTable();
            String js = JSONObject.toJSONString(bodyData.getJSONArray("genFiles"), SerializerFeature.WriteClassName);
            List<String> genFiles = JSONObject.parseArray(js, String.class);
            for (Map table : tables) {
                CodeGenerator gen = new CodeGenerator(
                    String.valueOf(table.get("TABLE_NAME")),
                    bodyData.getString("targetPath"),
                    bodyData.getString("pmenu"),
                    genFiles);
                gen.setDb(environment.getProperty("spring.datasource.url"),
                    environment.getProperty("spring.datasource.username"),
                    environment.getProperty("spring.datasource.password"));
                String msg = gen.create();
                System.out.println(msg);
            }
            return ApiReturnUtil.success("生成完成");
		} else {
            // 使用Builder生成列表页面
            XyBuilderForm formBuilder = new XyBuilderForm();
            formBuilder.addFormItem("tableNames", "表名称", "tags", "", new HashMap<String, Object>() {{
                put("disabled", true);
            }});
            formBuilder.addFormItem("targetPath", "生成路径", "text", "", new HashMap<String, Object>() {{
                put("tip", "生成路径类似com/xxx/xxx/xxx");
            }});
            formBuilder.addFormItem("pmenu", "菜单节点", "text", "", new HashMap<String, Object>() {{
                put("tip", "默认/content");
            }});
            formBuilder.addFormItem("genFiles", "生成文件", "checkbox", "", new HashMap<String, Object>() {{
                put("options", new ArrayList(){{
                    add(new HashMap<String, String>(){{
                        put("title", "Entity");
                        put("value", "Entity");
                    }});
                    add(new HashMap<String, String>(){{
                        put("title", "AdminController");
                        put("value", "AdminController");
                    }});
                    add(new HashMap<String, String>(){{
                        put("title", "Contriller");
                        put("value", "Contriller");
                    }});
                    add(new HashMap<String, String>(){{
                        put("title", "Service");
                        put("value", "Service");
                    }});
                    add(new HashMap<String, String>(){{
                        put("title", "Mapper");
                        put("value", "Mapper");
                    }});
                }});
            }});
            formBuilder.setFormValues(new HashMap<String, Object>() {{
                put("tableNames", request.getParameter("tableNames"));
                put("targetPath", "com/jiangruyi/summer/gen");
                put("pmenu", "/content");
                put("genFiles", new ArrayList());
            }});
            formBuilder.setConfig("submitApi", "/v1/admin/gen/genCodeBatch");
            Map<String, Object> formData = formBuilder.getData();

            // 添加一层formData
            JSONObject result = new JSONObject();
            result.put("formData", formData);

            return ApiReturnUtil.success(result);
        }
    }
}
