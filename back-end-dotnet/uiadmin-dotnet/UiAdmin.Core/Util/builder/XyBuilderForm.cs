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
    public class XyBuilderForm
    {
        public XyBuilderForm() {
            this.config.Add("continue", false);
            this.config.Add("submitApi", "");
            this.config.Add("itemDefaultPosition", "");
            this.config.Add("submitButtonTitle", "确认");
            this.config.Add("cancelButtonTitle", "取消");
            this.config.Add("footerButtonLength", "120px");
            this.config.Add("labelPosition", "left");
            this.config.Add("labelWidth", "100px");
            this.config.Add("defaultUploadDriver", "");
            this.config.Add("defaultUploadAction", "/v1/core/index/upload/");
            this.config.Add("defaultUploadMaxSize", 512);
        }
    
        // 配置
        private Dictionary<string, object> config = new Dictionary<string, object>();

        // 表单提交方法
        private string formMethod = "post";

        // 分栏
        private List<object> formCols = new List<object>();

        // 分组
        private List<object> formGroups = new List<object>();

        // TABS
        private List<object> formTabs = new List<object>();

        // 表单项目
        private List<object> formItems = new List<object>();

        // 表单验证规则
        private Dictionary<string, object> formRules = new Dictionary<string, object>();

        // 表单值合集
        private Dictionary<string, object> itemValues = new Dictionary<string, object>();

        // 表单值合集
        private Dictionary<string, object> formValues = new Dictionary<string, object>();

        // 修改设置
        public Object setConfig(String name, Object value){
            this.config.Add(name, value);
            return this;
        }

        // 表单提交方法
        public Object setFormMethod(String method){
            this.formMethod = method;
            return this;
        }

        // 添加分栏
        public Object addFormCol(String name,Dictionary<string, object> span, List<object> itemList, Dictionary<string, object> extra){
            Dictionary<string, object> col = new Dictionary<string, object>();
            col.Add("name", name);
            col.Add("span", span);
            col.Add("itemList", itemList);
            col.Add("extra", extra);
            this.formCols.Add(col);
            return this;
        }

        // 添加分组
        public Object addFormGroup(String name,String title, List<object> itemList, Dictionary<string, object> extra){
            Dictionary<string, object> col = new Dictionary<string, object>();
            col.Add("name", name);
            col.Add("title", title);
            col.Add("itemList", itemList);
            col.Add("extra", extra);
            this.formGroups.Add(col);
            return this;
        }

        // 添加表单项目
        public Object addFormItem(String name, String title, String type, Object value){
            Dictionary<string, object> col = new Dictionary<string, object>();
            col.Add("name", name);
            col.Add("title", title);
            col.Add("type", type);
            col.Add("value", value);
            col.Add("extra", new Dictionary<string, object>());
            this.formItems.Add(col);
            return this;
        }

        // 添加表单项目
        public Object addFormItem(String name,String title, String type, Object value, Dictionary<string, object> extra){
            Dictionary<string, object> col = new Dictionary<string, object>();
            col.Add("name", name);
            col.Add("title", title);
            col.Add("type", type);
            col.Add("value", value);
            col.Add("extra", extra);
            this.formItems.Add(col);
            return this;
        }

        // 添加表单验证规则
        public Object addFormRule(String name, List<object> rule){
            this.formRules.Add(name, rule);
            return this;
        }

        // 设置表单值
        public Object setFormValues(Dictionary<string, object> data){
            this.itemValues = data;
            return this;
        }

        // 获取数据
        public Dictionary<string, object> getData() {
            Dictionary<string, object> data = new Dictionary<string, object>() {
                {"alertList", new Dictionary<string, object>() {
                    {"top", new List<object>()},
                    {"bottom", new List<object>()}
                }},
                {"formMethod", formMethod}, 
                {"formCols", formCols},
                {"formGroups", formGroups},
                {"formTabs", formTabs},
                {"formItems", formItems},
                {"formRules", formRules},
                {"formValues", formValues},
                {"config", config},
            };
            return data;
        }
    }
}