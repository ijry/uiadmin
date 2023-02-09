using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Http;
using Microsoft.Extensions.Logging;
using System.Net;
using System.Text;
using Newtonsoft.Json.Linq;
using UiAdmin.Core.Util;

namespace UiAdmin.Core.Util.Builder
{
    public class XyBuilderList
    {
        public XyBuilderList() {
            this.config.Add("listExpandAll", false);
            this.config.Add("modalDefaultWidth", "800px");
            this.dataListParams.Add("expandKey", "title");
            this.dataListParams.Add("tableName", "");
            this.dataListParams.Add("selectable", true);
            this.dataListParams.Add("selectType", "checkbox");
        }

        // 顶部按钮
        private List<object> topButtonList = new List<object>();

        // 右侧按钮
        private List<object> rightButtonList = new List<object>();

        // 列
        private List<object> columns = new List<object>();

        // 数据列表
        private List<object> dataList = new List<object>();

        // 分页
        private Dictionary<String, Object> dataPage = new Dictionary<String, Object>();

        // 筛选表单项目
        private List<object> filterItems = new List<object>();

        // 配置
        private Dictionary<String, Object> config = new Dictionary<String, Object>();

        // 列表配置
        private Dictionary<String, Object> dataListParams = new Dictionary<String, Object>();

        // 添加顶部按钮
        public XyBuilderList addTopButton(String name,String  title, Dictionary<String, Object> pageData){
            Dictionary<String, Object> col = new Dictionary<String, Object>();
            col.Add("name", name);
            col.Add("title", title);
            col.Add("pageData", pageData);
            col.Add("style", new Dictionary<String, Object>());
            this.topButtonList.Add(col);
            return this;
        }

        // 添加顶部按钮
        public XyBuilderList addTopButton(String name,String  title, Dictionary<String, Object> pageData, Dictionary<String, Object> style){
            Dictionary<String, Object> col = new Dictionary<String, Object>();
            col.Add("name", name);
            col.Add("title", title);
            col.Add("pageData", pageData);
            col.Add("style", style);
            this.topButtonList.Add(col);
            return this;
        }

        // 添加右侧按钮
        public XyBuilderList addRightButton(String name,String  title, Dictionary<String, Object> pageData){
            Dictionary<String, Object> col = new Dictionary<String, Object>();
            col.Add("name", name);
            col.Add("title", title);
            col.Add("pageData", pageData);
            col.Add("style", new Dictionary<String, Object>());
            this.rightButtonList.Add(col);
            return this;
        }
        
        // 添加右侧按钮
        public XyBuilderList addRightButton(String name,String  title, Dictionary<String, Object> pageData, Dictionary<String, Object> style){
            Dictionary<String, Object> col = new Dictionary<String, Object>();
            col.Add("name", name);
            col.Add("title", title);
            col.Add("pageData", pageData);
            col.Add("style", style);
            this.rightButtonList.Add(col);
            return this;
        }

        // 添加列
        public XyBuilderList addColumn(String name, String title){
            Dictionary<String, Object> col = new Dictionary<String, Object>();
            col.Add("name", name);
            col.Add("title", title);
            col.Add("extra", new List<object>());
            this.columns.Add(col);
            return this;
        }

        // 添加列
        public XyBuilderList addColumn(String name, String title, Dictionary<String, Object> extra){
            Dictionary<String, Object> col = new Dictionary<String, Object>();
            col.Add("name", name);
            col.Add("title", title);
            col.Add("extra", extra);
            this.columns.Add(col);
            return this;
        }

        // 设置数据列表
        public XyBuilderList setDataList(List<object> data){
            this.dataList = data;
            return this;
        }

        // 设置分页
        public XyBuilderList setDataPage(long total, long page, long limit){
            this.dataPage.Add("total", total);
            this.dataPage.Add("page", page);
            this.dataPage.Add("limit", limit);
            return this;
        }

        // 添加搜索
        public XyBuilderList addFilterItem(String name, String title, String type, Object value){
            Dictionary<String, Object> col = new Dictionary<String, Object>();
            col.Add("name", name);
            col.Add("title", title);
            col.Add("type", type);
            col.Add("value", value);
            col.Add("extra", new Dictionary<String, Object>());
            this.filterItems.Add(col);
            return this;
        }

        // 添加搜索
        public XyBuilderList addFilterItem(String name, String title, String type, Object value, Dictionary<String, Object> extra){
            Dictionary<String, Object> col = new Dictionary<String, Object>();
            col.Add("name", name);
            col.Add("title", title);
            col.Add("type", type);
            col.Add("value", value);
            col.Add("extra", extra);
            this.filterItems.Add(col);
            return this;
        }

        // 修改列表设置
        public XyBuilderList setListConfig(String name, Object value){
            this.config.Add(name, value);
            return this;
        }

        // 修改设置
        public XyBuilderList setConfig(String name, Object value){
            this.config.Add(name, value);
            return this;
        }
        
        // 获取数据
        public Dictionary<String, Object> getData() {
            Dictionary<String, Object> data = new Dictionary<String, Object>() {
                {"alertList", new Dictionary<String, Object>() {
                    {"top", new List<object>()},
                    {"bottom", new List<object>()}
                }},
                {"dataList", dataList},
                {"dataListParams", dataListParams},
                {"topButtonList", topButtonList},
                {"rightButtonList", rightButtonList},
                {"columns", columns},
                {"dataPage", dataPage},
                {"filterItems", filterItems},
                {"filterValues", new List<object>()},
                {"filterExtra", new List<object>()},
                {"countList", new List<object>()},
                {"config", config},
            };
            return data;
        }
    }
}