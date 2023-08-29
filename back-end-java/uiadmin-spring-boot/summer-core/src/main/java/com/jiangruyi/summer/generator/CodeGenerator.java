package com.jiangruyi.summer.generator;

import java.io.*;
import java.sql.*;
import java.nio.charset.StandardCharsets;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
 * UiAdmin(Summer)代码生成工具
 * 本公举参考：https://www.cnblogs.com/huanzi-qch/p/14927738.html实现
 */
public class CodeGenerator {

    /**
     * 程序自动设置
     */
    private String tableName; // 表名
    private String tableComment; // 表注释
    private String filePath; // 最终文件生成位置
    private List<String> genFiles; // 生成文件

    /**
     * 数据连接相关，需要手动设置
     */
    private String URL = "";
    private String USERNAME = "";
    private String PASSWORD = "";
    private String DRIVER_CLASSNAME = "com.mysql.cj.jdbc.Driver";

    /**
     * 基础路径，需要手动设置
     */
    // 模板路径要获取jar中的文件
    // 模板文件位置
    // private String tlfPath = System.getProperty("user.dir") + "/src/main/resources/tlf/";
    private String tlfPath = "tlf/";
    private String targetPackage = ""; // 生成文件目标包
    private String pmenu = "/content"; // 父菜单

    public static void main(String[] args) {
        String[] tables = { "pubplan" };
        for (String table : tables) {
            String msg = new CodeGenerator(table, "com/jiangruyi/summer/gen", "/content").create();
            System.out.println(msg);
        }
    }

    /**
     * 构造参数，设置表名
     * String targetPath  // 生成文件目标路径
     */
    CodeGenerator(String tableName, String targetPath, String pmenu) {
        // 设置表名
        this.tableName = tableName;
        this.pmenu = pmenu;

        // java.net.URL base = this.getClass().getResource("");
        // System.out.println(base);

        // 拼接完整最终位置 System.getProperty("user.dir") 获取的是项目所在路径，如果我们是子项目，则需要添加一层路径
        filePath = System.getProperty("user.dir") +"/src/main/java/" + targetPath + "/";
        targetPackage = targetPath.replaceAll("/", ".");
    }

    /**
     * 构造参数，设置表名
     * String targetPath  // 生成文件目标路径
     */
    CodeGenerator(String tableName, String targetPath, String pmenu, List genFiles) {
        // 设置表名
        this.tableName = tableName;
        this.genFiles = genFiles;
        this.pmenu = pmenu;

        // java.net.URL base = this.getClass().getResource("");
        // System.out.println(base);

        // 拼接完整最终位置 System.getProperty("user.dir") 获取的是项目所在路径，如果我们是子项目，则需要添加一层路径
        filePath = System.getProperty("user.dir") +"/src/main/java/" + targetPath + "/";
        targetPackage = targetPath.replaceAll("/", ".");
    }

    /**
     * 构造参数，设置表名
     */
    public void setDb(String url, String username, String password) {
        URL = url;
        USERNAME = username;
        PASSWORD = password;
    }

    /**
     * 读取模板，设置内容，生成文件
     * 
     * @param templatePath    模板文件路径
     * @param outputFile      文件生成路径
     * @param tableInfos      表字段信息
     * @param customParameter 自定义参数
     */
    private void writer(String templatePath, String outputFile, List<TableInfo> tableInfos,
            Map<String, String> customParameter) {
        templatePath = templatePath.replaceAll("\\$", "/");

        // 主键
        TableInfo prikey = new TableInfo();

        // for循环标识
        boolean forFlag = false;
        StringBuilder forContent = new StringBuilder(1024);

        // 驼峰标识映射后的表名
        String replacement = StringUtil.captureName(StringUtil.camelCaseName(tableName));

        // 遍历属性
        for (TableInfo tableInfo : tableInfos) {
            // 主键
            if ("PRI".equals(tableInfo.getColumnKey())) {
                prikey = tableInfo;
                break;
            }
        }

        // 读取模板文件
        try (
            InputStream is = getClass().getClassLoader().getResourceAsStream(templatePath);
            InputStreamReader fileReader = new InputStreamReader(is, StandardCharsets.UTF_8);
            BufferedReader reader = new BufferedReader(fileReader)) {
            // 生成文件
            File file = FileUtil.createFile(outputFile);
            StringBuilder stringBuilder = new StringBuilder(1024);

            // 读取模板文件，拼接文件内容
            Object[] lines = reader.lines().toArray();
            for (Object o : lines) {
                String line = String.valueOf(o);

                /* 设置值 */

                // ${targetPackage} 目标包名
                if (line.contains("${targetPackage}")) {
                    line = line.replaceAll("\\$\\{targetPackage}", targetPackage);
                }

                // ${pmenu} 父菜单
                if (line.contains("${pmenu}")) {
                    line = line.replaceAll("\\$\\{pmenu}", pmenu);
                }

                // ${tableName} 表名称，例如：tb_user
                if (line.contains("${tableName}")) {
                    line = line.replaceAll("\\$\\{tableName}", tableName);
                }

                // ${tableComment} 表注释，例如：tb_user
                if (line.contains("${tableComment}")) {
                    line = line.replaceAll("\\$\\{tableComment}", tableComment);
                }

                // ${entity} 实体类名称，例如：TbUser
                if (line.contains("${entity}")) {
                    line = line.replaceAll("\\$\\{entity}", replacement);
                }

                // ${entityFirstToLowerCase} 实体类名称首字母小写，例如：tbUser
                if (line.contains("${entityFirstToLowerCase}")) {
                    line = line.replaceAll("\\$\\{entityFirstToLowerCase}", StringUtil.camelCaseName(tableName));
                }

                // ${entityToLowerCase} 实体类名称全小写，例如：tbuser
                if (line.contains("${entityToLowerCase}")) {
                    line = line.replaceAll("\\$\\{entityToLowerCase}", replacement.toLowerCase());
                }

                // ${priDataType} 实体类主键类型，例如：String
                if (line.contains("${priDataType}")) {
                    line = line.replaceAll("\\$\\{priDataType}", StringUtil.typeMapping(prikey.getDataType()));
                }

                // 处理自定义参数
                line = customParameter(line, customParameter);

                // 先取得循环体的内容
                if (forFlag) {
                    forContent.append(line).append("\n");
                }

                // 是否为for循环遍历表字段
                if (line.contains("#for")) {
                    forFlag = true;
                }
                if (line.contains("#end")) {
                    forFlag = false;
                    line = line.replaceAll("#end", "");
                }

                // 遍历循环体的内容，并设置值
                if (!forFlag && forContent.length() > 0) {
                    // 遍历表字段
                    for (TableInfo tableInfo : tableInfos) {
                        String tableColumns = forContent.toString()
                                // 表字段信息：类型、名称、注释
                                .replaceAll("\\$\\{tableInfo.dataType}",
                                        StringUtil.typeMapping(tableInfo.getDataType()))
                                .replaceAll("\\$\\{tableInfo.columnName}",
                                        StringUtil.camelCaseName(tableInfo.getColumnName()))
                                .replaceAll("\\$\\{tableInfo.columnComment}", tableInfo.getColumnComment())
                                .replaceAll("\\$\\{tableInfo.columnCommentOrigin}", tableInfo.getColumnCommentOrigin());

                        // 清除多余#end，以及换行符
                        tableColumns = tableColumns.replaceAll("#end", "").replaceAll("\n", "");

                        // 设置是否主键、是否自增
                        String pri = "", autoIncrement = "";
                        // 主键
                        if ("PRI".equals(tableInfo.getColumnKey())) {
                            pri = "\n    @Id // jpa\n";
                            // 自增id
                            if ("auto_increment".equals(tableInfo.getExtra())) {
                                autoIncrement = "@TableId(type = IdType.AUTO) // mybatis-plus\n"
                                    + "@GeneratedValue(strategy= GenerationType.AUTO) // jpa";
                            } else {
                                pri = pri + "    @TableId  // mybatis-plus\n";
                            }
                        }

                        // 时间格式化
                        String ifDatetime = "";
                        if (tableInfo.getColumnName().contains("time")) {
                            ifDatetime = "@JSONField(format=\"yyyy-MM-dd HH:mm:ss\")";
                        }

                        // 列表参数
                        String listExtra = "\n";
                        if (!"delete_time".equals(tableInfo.getColumnName())
                            // && !tableInfo.getColumnName().contains("content")
                            ) {
                            if (tableInfo.getColumnName().contains("title")) {
                                listExtra = listExtra + "            put(\"minWidth\", \"220\");\n";
                            } else if (tableInfo.getColumnName().contains("_time")) {
                                listExtra = listExtra + "            put(\"width\", \"150\");\n";
                            } else if (tableInfo.getColumnName().contains("_id")
                                || tableInfo.getColumnName().equals("id")) {
                                listExtra = listExtra + "            put(\"width\", \"170\");\n";
                            } else if (tableInfo.getColumnName().contains("img")
                                || tableInfo.getColumnName().contains("image")
                                || tableInfo.getColumnName().contains("cover")
                                || tableInfo.getColumnName().contains("avatar")
                                || tableInfo.getColumnName().contains("pic")) {
                                    listExtra = listExtra + "            put(\"type\", \"image\");\n"
                                        + "            put(\"extend\", new ArrayList(){{\n";
                                    listExtra = listExtra+"                }});\n";
                            } else if (tableInfo.getColumnName().contains("content")) {
                                listExtra = listExtra + "            put(\"type\", \"multitext\");\n"
                                        + "            put(\"options\", new ArrayList(){{\n";
                                    listExtra = listExtra+"                }});\n";
                            } else if (tableInfo.getColumnName().contains("qr")) {
                                listExtra = listExtra + "            put(\"type\", \"qrcode\");\n"
                                        + "            put(\"options\", new ArrayList(){{\n";
                                    listExtra = listExtra+"                }});\n";
                            } else if (tableInfo.getColumnName().contains("progress")) {
                                listExtra = listExtra + "            put(\"type\", \"progress\");\n"
                                        + "            put(\"options\", new ArrayList(){{\n";
                                    listExtra = listExtra+"                }});\n";
                            } else if (tableInfo.getColumnName().contains("url")
                                || tableInfo.getColumnName().contains("link")) {
                                listExtra = listExtra + "            put(\"type\", \"url\");\n"
                                        + "            put(\"title\", \"点击访问\");\n"
                                        + "            put(\"options\", new ArrayList(){{\n";
                                    listExtra = listExtra+"                }});\n";
                            } else if (tableInfo.getColumnName().contains("json")) {
                                    listExtra = listExtra + "            put(\"type\", \"formlist\");\n"
                                        + "            put(\"extend\", new ArrayList(){{\n";
                                    listExtra = listExtra+"            }});\n";
                            // } else if (tableInfo.getColumnName().contains("status")
                            //     || tableInfo.getColumnName().contains("state")) {
                            //         listExtra = listExtra + "            put(\"type\", \"tag\");\n"
                            //             + "            put(\"options\", new ArrayList(){{\n";
                            //         listExtra = listExtra + "                add(\"禁用\");\n"
                            //             + "                add(\"启用\");\n";
                            //         listExtra = listExtra + "            }});\n";
                            // }
                            } else if (tableInfo.getColumnOptions().size() > 0) {
                                listExtra = listExtra + "            put(\"type\", \"tag\");\n"
                                        + "            put(\"options\", new ArrayList(){{\n";
                                        for (Map mp : tableInfo.getColumnOptions()) {
                                            listExtra = listExtra
                                                + "                add(new HashMap(){{\n"
                                                + "                    put("
                                                    + "\"title\", \"" + mp.get("title") + "\""
                                                + ");\n"
                                                + "                    put("
                                                    + "\"value\", \"" + mp.get("value") + "\""
                                                + ");\n"
                                                + "                    put("
                                                    + "\"type\", \"" + mp.get("type") + "\""
                                                + ");\n"
                                                + "                }});\n";
                                        }
                                    listExtra = listExtra + "            }});\n";
                            }
                            // listExtra = listExtra + "            put(\"minWidth\", \"60px\");\n";
                        } else {
                            listExtra = "";
                        }
                        
                        // 表单参数
                        String formExtra = "\n";
                        String formType = "text";
                        if (!"delete_time".equals(tableInfo.getColumnName())) {
                            if (tableInfo.getColumnName().contains("title")) {
                                formType = "text";
                                formExtra = formExtra + "";
                            } else if (tableInfo.getColumnName().contains("_time")) {
                                formType = "datetimepicker";
                            } else if (tableInfo.getColumnName().contains("img")
                                || tableInfo.getColumnName().contains("image")
                                || tableInfo.getColumnName().contains("cover")
                                || tableInfo.getColumnName().contains("avatar")
                                || tableInfo.getColumnName().contains("pic")) {
                                formType = "image";
                                    formExtra = formExtra + "            put(\"maxSize\", \"5120\");\n";
                                    formExtra = formExtra + "            put(\"maxFileSize\", \"5120000\");\n";
                                    formExtra = formExtra + "            put(\"action\", \"/api/v1/upload/upload\");\n";
                            } else if (tableInfo.getColumnName().contains("file")
                                || tableInfo.getColumnName().contains("atte")) {
                                formType = "file";
                                    formExtra = formExtra + "            put(\"maxSize\", \"5120\");\n";
                                    formExtra = formExtra + "            put(\"maxFileSize\", \"5120000\");\n";
                                    formExtra = formExtra + "            put(\"action\", \"/api/v1/upload/upload\");\n";
                            } else if (tableInfo.getColumnName().contains("content")) {
                                formType = "html";
                                formExtra = formExtra + "            put(\"maxSize\", \"5120\");\n";
                                formExtra = formExtra + "            put(\"maxFileSize\", \"5120000\");\n";
                                formExtra = formExtra + "            put(\"action\", \"/api/v1/upload/upload\");\n";
                                formExtra = formExtra + "            put(\"newyearTitle\", \"你好！\");\n";
                            } else if (tableInfo.getColumnName().contains("json")) {
                                formType = "textarea";
                            } else if (tableInfo.getColumnName().contains("status")
                                || tableInfo.getColumnName().contains("state")) {
                                formType = "radio";
                                formExtra = formExtra + "                put(\"options\", new ArrayList(){{\n";
                                for (Map mp : tableInfo.getColumnOptions()) {
                                    formExtra = formExtra
                                        + "                    add(new HashMap(){{\n"
                                        + "                        put("
                                            + "\"title\", \"" + mp.get("title") + "\""
                                        + ");\n"
                                        + "                        put("
                                            + "\"value\", \"" + mp.get("value") + "\""
                                        + ");\n"
                                        + "                        put("
                                            + "\"type\", \"" + mp.get("type") + "\""
                                        + ");\n"
                                        + "                    }});\n";
                                }
                                formExtra = formExtra + "            }});\n";
                            } else if (tableInfo.getColumnName().contains("rate")) {
                                formType = "rate";
                                formExtra = formExtra + "                put(\"options\", new ArrayList(){{\n";
                                formExtra = formExtra + "                }});\n";
                            } else if (tableInfo.getColumnName().contains("map_fee")) {
                                formType = "amap";
                                formExtra = formExtra + "                put(\"mapType\", \"polygonFee\");\n";
                                formExtra = formExtra + "                put(\"options\", new ArrayList(){{\n";
                                formExtra = formExtra + "                }});\n";
                            } else if (tableInfo.getColumnName().contains("map")) {
                                formType = "amap";
                                formExtra = formExtra + "                put(\"mapType\", \"position\");\n";
                                formExtra = formExtra + "                put(\"options\", new ArrayList(){{\n";
                                formExtra = formExtra + "                }});\n";
                            } else if (tableInfo.getColumnName().contains("progress")) {
                                formType = "slider";
                                formExtra = formExtra + "                put(\"options\", new ArrayList(){{\n";
                                formExtra = formExtra + "                }});\n";
                            } else if (tableInfo.getColumnName().contains("tags")) {
                                formType = "tags";
                                formExtra = formExtra + "                put(\"options\", new ArrayList(){{\n";
                                formExtra = formExtra + "                }});\n";
                            } else if (tableInfo.getColumnOptions().size() > 0) {
                                formType = "radio";
                                formExtra = formExtra + "                put(\"options\", new ArrayList(){{\n";
                                for (Map mp : tableInfo.getColumnOptions()) {
                                    formExtra = formExtra
                                        + "                    add(new HashMap(){{\n"
                                        + "                        put("
                                            + "\"title\", \"" + mp.get("title") + "\""
                                        + ");\n"
                                        + "                        put("
                                            + "\"value\", \"" + mp.get("value") + "\""
                                        + ");\n"
                                        + "                        put("
                                            + "\"type\", \"" + mp.get("type") + "\""
                                        + ");\n"
                                        + "                    }});\n";
                                }
                                formExtra = formExtra + "                }});\n";
                            }
                            // formExtra = formExtra + "            put(\"minWidth\", \"60px\");\n";
                        } else {
                            formExtra = "";
                        }

                        // 筛选参数
                        String filterExtra = "";
                        String filterType = "text";
                        if (!"deleteTime".equals(tableInfo.getColumnName())) {
                            if (tableInfo.getColumnOptions().size() > 0) {
                                filterType = "select";
                                filterExtra = filterExtra + "\n            put(\"options\", new ArrayList(){{\n";
                                for (Map mp : tableInfo.getColumnOptions()) {
                                    filterExtra = filterExtra
                                        + "                add(new HashMap(){{\n"
                                        + "                    put("
                                            + "\"title\", \"" + mp.get("title") + "\""
                                        + ");\n"
                                        + "                    put("
                                            + "\"value\", \"" + mp.get("value") + "\""
                                        + ");\n"
                                        + "                    put("
                                            + "\"type\", \"" + mp.get("type") + "\""
                                        + ");\n"
                                        + "                }});\n";
                                }
                                filterExtra = filterExtra + "            }});\n";
                            }
                        }

                        // 替换参数
                        tableColumns = tableColumns
                            .replaceAll("#ifPri", pri)
                            .replaceAll("#ifAutoIncrement", autoIncrement)
                            .replaceAll("#ifDatetime", ifDatetime)
                            .replaceAll("#listExtra", listExtra)
                            .replaceAll("#formExtra", formExtra)
                            .replaceAll("#filterExtra", filterExtra)
                            .replaceAll("\\$\\{tab}", "    ")
                            .replaceAll("\\$\\{tableInfo.formType}", formType)
                            .replaceAll("\\$\\{tableInfo.filterType}", filterType)
                            .replaceAll("\\$\\{ln}", "\n");

                        // 处理自定义参数
                        tableColumns = "" + customParameter(tableColumns, customParameter);

                        // 前补tab，后补换行符
                        stringBuilder.append("").append(tableColumns).append("\n");
                    }
                    // 置空
                    forContent.setLength(0);
                }

                if (!forFlag) {
                    stringBuilder.append(line).append("\n");
                } else {
                    // System.out.println(stringBuilder.toString());
                }
            }
            // 写入数据到到文件中
            FileUtil.fileWriter(file, stringBuilder);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void writer(String templatePath, String outputFile, List<TableInfo> tableInfos) {
        writer(templatePath, outputFile, tableInfos, new HashMap<>(0));
    }

    /**
     * 处理自定义参数
     */
    private String customParameter(String str, Map<String, String> customParameter) {
        for (String key : customParameter.keySet()) {
            str = str.replaceAll("\\$\\{" + key + "}", customParameter.get(key));
        }
        return str;
    }

    /**
     * file工具类
     */
    private static class FileUtil {
        /**
         * 创建文件
         *
         * @param pathNameAndFileName 路径跟文件名
         * @return File对象
         */
        private static File createFile(String pathNameAndFileName) {
            File file = new File(pathNameAndFileName);
            try {
                // 获取父目录
                File fileParent = file.getParentFile();
                if (!fileParent.exists()) {
                    fileParent.mkdirs();
                }
                // 创建文件
                if (!file.exists()) {
                    file.createNewFile();
                }
            } catch (Exception e) {
                file = null;
                System.err.println("新建文件操作出错");
                e.printStackTrace();
            }
            return file;
        }

        /**
         * 字符流写入文件
         *
         * @param file          file对象
         * @param stringBuilder 要写入的数据
         */
        private static void fileWriter(File file, StringBuilder stringBuilder) {
            // 字符流
            try {
                // 创建一个FileOutputStream对象
                FileOutputStream fos = new FileOutputStream(file);

                // 创建一个Writer对象，设置字符编码为UTF-8
                Writer writer = new OutputStreamWriter(fos, StandardCharsets.UTF_8);

                // 写入内容
                writer.write(stringBuilder.toString());

                // 关闭文件流
                writer.close();
                fos.close();

                // FileWriter resultFile = new FileWriter(file, false); // true,则追加写入 false,则覆盖写入
                // PrintWriter myFile = new PrintWriter(resultFile);
                // // 写入
                // myFile.println(stringBuilder.toString());

                // myFile.close();
                // resultFile.close();
            } catch (Exception e) {
                System.err.println("写入操作出错");
                e.printStackTrace();
            }
        }
    }

    /**
     * 字符串处理工具类
     */
    private static class StringUtil {
        /**
         * 数据库类型->JAVA类型
         *
         * @param dbType 数据库类型
         * @return JAVA类型
         */
        private static String typeMapping(String dbType) {
            String javaType;
            if ("int|integer".contains(dbType)) {
                javaType = "Integer";
            } else if ("float|double|decimal|real".contains(dbType)) {
                javaType = "Double";
            } else if ("date|time|datetime|timestamp".contains(dbType)) {
                javaType = "Date";
            } else {
                javaType = "String";
            }
            return javaType;
        }

        /**
         * 驼峰转换为下划线
         */
        private static String underscoreName(String camelCaseName) {
            StringBuilder result = new StringBuilder(1024);
            if (camelCaseName != null && camelCaseName.length() > 0) {
                result.append(camelCaseName.substring(0, 1).toLowerCase());
                for (int i = 1; i < camelCaseName.length(); i++) {
                    char ch = camelCaseName.charAt(i);
                    if (Character.isUpperCase(ch)) {
                        result.append("_");
                        result.append(Character.toLowerCase(ch));
                    } else {
                        result.append(ch);
                    }
                }
            }
            return result.toString();
        }

        /**
         * 首字母大写
         */
        private static String captureName(String name) {
            char[] cs = name.toCharArray();
            cs[0] -= 32;
            return String.valueOf(cs);

        }

        /**
         * 下划线转换为驼峰
         */
        private static String camelCaseName(String underscoreName) {
            StringBuilder result = new StringBuilder(1024);
            if (underscoreName != null && underscoreName.length() > 0) {
                boolean flag = false;
                for (int i = 0; i < underscoreName.length(); i++) {
                    char ch = underscoreName.charAt(i);
                    if ("_".charAt(0) == ch) {
                        flag = true;
                    } else {
                        if (flag) {
                            result.append(Character.toUpperCase(ch));
                            flag = false;
                        } else {
                            result.append(ch);
                        }
                    }
                }
            }
            return result.toString();
        }
    }

    /**
     * JDBC连接数据库工具类
     */
    private class DBConnectionUtil {

        {
            // 1、加载驱动
            try {
                Class.forName(DRIVER_CLASSNAME);
            } catch (ClassNotFoundException e) {
                e.printStackTrace();
            }
        }

        /**
         * 返回一个Connection连接
         */
        Connection getConnection() {
            Connection conn = null;
            // 2、连接数据库
            try {
                conn = DriverManager.getConnection(URL, USERNAME, PASSWORD);
            } catch (SQLException e) {
                e.printStackTrace();
            }
            return conn;
        }

        /**
         * 关闭Connection，Statement连接
         */
        public void close(Connection conn, Statement stmt) {
            try {
                conn.close();
                stmt.close();
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }

        /**
         * 关闭Connection，Statement，ResultSet连接
         */
        public void close(Connection conn, Statement stmt, ResultSet rs) {
            try {
                close(conn, stmt);
                rs.close();
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }

    }

    /**
     * 表结构信息实体类
     */
    private class TableInfo {
        private String columnName; // 字段名
        private String dataType; // 字段类型
        private String columnComment; // 字段注释
        private String columnCommentOrigin; // 字段注释原注释
        private String columnKey; // 主键
        private String extra; // 主键类型
        private List<Map> columnOptions; // 选项

        public String getColumnName() {
            return columnName;
        }

        public void setColumnName(String columnName) {
            this.columnName = columnName;
        }

        public String getDataType() {
            return dataType;
        }

        public void setDataType(String dataType) {
            this.dataType = dataType;
        }

        public String getColumnComment() {
            return columnComment;
        }

        public void setColumnComment(String columnComment) {
            this.columnComment = columnComment;
        }

        public String getColumnCommentOrigin() {
            return columnCommentOrigin;
        }

        public void setColumnCommentOrigin(String columnCommentOrigin) {
            this.columnCommentOrigin = columnCommentOrigin;
        }

        public String getColumnKey() {
            return columnKey;
        }

        public void setColumnKey(String columnKey) {
            this.columnKey = columnKey;
        }

        public String getExtra() {
            return extra;
        }

        public void setExtra(String extra) {
            this.extra = extra;
        }

        public List<Map> getColumnOptions() {
            return columnOptions;
        }

        public void setColumnOptions(List columnOptions) {
            this.columnOptions = columnOptions;
        }
    }

    /**
     * 获取表结构信息
     * 目前仅支持mysql
     */
    private List<TableInfo> getTableInfo() {
        Connection conn = null;
        PreparedStatement ps = null;
        ResultSet rs = null;
        ArrayList<TableInfo> list = new ArrayList<>();
        DBConnectionUtil dBConnectionUtil = new DBConnectionUtil();
        try {
            conn = dBConnectionUtil.getConnection();

            // 表字段信息
            String sql = "select column_name,data_type,column_comment,column_key,extra from information_schema.columns where table_schema = (select database()) and table_name=?";
            ps = conn.prepareStatement(sql);
            ps.setString(1, tableName);
            rs = ps.executeQuery();
            while (rs.next()) {
                TableInfo tableInfo = new TableInfo();
                // 列名，全部转为小写
                tableInfo.setColumnName(rs.getString("column_name").toLowerCase());
                // 列类型
                tableInfo.setDataType(rs.getString("data_type"));
                // 列注释
                tableInfo.setColumnComment(
                    rs.getString("column_comment")
                    .replaceAll("[\\(\\（)].*[\\)\\）]", "")
                );
                tableInfo.setColumnCommentOrigin(
                    rs.getString("column_comment")
                );
                // 获取options
                // 从字段注释中匹配如，优先级(1:低,2:中,3:高)"
                List<Map> optionsList = new ArrayList();
                String colComment = rs.getString("column_comment");
                Pattern pattern = Pattern.compile("[\\(\\（)].*[\\)\\）]");
                Matcher matcher = pattern.matcher(colComment);
                if (matcher.find() && colComment != null) {
                    String finded = matcher.group(0);
                    if (finded != null) {
                        finded = finded.substring(1, finded.length() -1);
                        String[] findedList = finded.split(",");
                        for (String each : findedList) {
                            String[] eachps = each.split(":");
                            if (eachps.length == 2) {
                                optionsList.add(new HashMap(){{
                                    put("value", eachps[0]);
                                    put("title", eachps[1]);
                                    switch(String.valueOf(eachps[0])) {
                                        case "-2":
                                            put("type", "info");
                                            break;
                                        case "-1":
                                            put("type", "warning");
                                            break;
                                        case "0":
                                            put("type", "danger");
                                            break;
                                        case "1":
                                            put("type", "success");
                                            break;
                                        case "2":
                                            put("type", "success");
                                            break;
                                        default:
                                            put("type", "primary");
                                    }
                                }});
                                System.out.println("Found value: " + eachps[0] + eachps[1]);
                            }
                        }
                    }
                }
                tableInfo.setColumnOptions(optionsList);
                // 主键
                tableInfo.setColumnKey(rs.getString("column_key"));
                // 主键类型
                tableInfo.setExtra(rs.getString("extra"));
                list.add(tableInfo);
            }

            // 表注释
            sql = "select table_comment from information_schema.tables where table_schema = (select database()) and table_name=?";
            ps = conn.prepareStatement(sql);
            ps.setString(1, tableName);
            rs = ps.executeQuery();
            while (rs.next()) {
                // 表注释 // 去除[]
                tableComment = rs.getString("table_comment")
                    .replaceAll("\\[.*\\]", "")
                    .replaceAll("\\(.*\\)", "")
                    .replaceAll("\\（.*\\）", "")
                    .replaceAll("表", "");
            }
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            if (rs != null) {
                dBConnectionUtil.close(conn, ps, rs);
            }
        }
        return list;
    }

    /**
     * 快速创建，供外部调用，调用之前先设置一下项目的基础路径
     */
    public String create() {
        System.out.println("生成路径位置：" + filePath);

        // 获取表信息
        List<TableInfo> tableInfo = getTableInfo();

        // 驼峰标识映射后的表名
        String captureName = StringUtil.captureName(StringUtil.camelCaseName(tableName));

        // 自定义参数
        HashMap<String, String> customParameter = new HashMap<>(2);
        customParameter.put("author", "作者：Auto Generator By 'summer'");
        customParameter.put("date", "生成日期：" + new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format(new Date()));

        // 读取模板、生成代码
        if (this.genFiles.contains("AdminController")) {
            writer(tlfPath + "admin.tlf",
                filePath + "admin/" + captureName + "Controller.java",
                tableInfo, customParameter);
        }
        if (this.genFiles.contains("Controller")) {
            writer(tlfPath + "controller.tlf",
                filePath + "controller/" + captureName + "Controller.java",
                tableInfo, customParameter);
        }
        if (this.genFiles.contains("Entity")) {
            writer(tlfPath + "entity.tlf",
                filePath + "entity/" + captureName + ".java",
                tableInfo, customParameter);
        }
        // writer(tlfPath + "entityvo.tlf",
        // filePath + "vo\\" + captureName + "Vo.java",
        // tableInfo, customParameter);
        // if (this.genFiles.contains("Repository")) {
        // writer(tlfPath + "repository.tlf",
        //      filePath + "repository\\" + captureName + "Repository.java",
        //      tableInfo, customParameter);
        // }
        if (this.genFiles.contains("Mapper")) {
            writer(tlfPath + "mapper.tlf",
                filePath + "mapper/" + captureName + "Mapper.java",
                tableInfo, customParameter);
        }
        if (this.genFiles.contains("Service")) {
            writer(tlfPath + "service.tlf",
                filePath + "service/I" + captureName + "Service.java",
                tableInfo, customParameter);
            writer(tlfPath + "serviceimpl.tlf",
                filePath + "service/impl/" + captureName + "ServiceImpl.java",
                tableInfo, customParameter);
        }

        return tableName + " 后台代码生成完毕！";
    }
}
