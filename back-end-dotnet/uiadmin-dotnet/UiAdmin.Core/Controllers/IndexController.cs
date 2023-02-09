using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Http;
using Microsoft.Extensions.Logging;
using System.Net;
using System.Text;
using UiAdmin.Core.Util;

namespace UiAdmin.Core.Controllers
{
    [ApiController]
    public class IndexController : ControllerBase
    {

        private readonly ILogger<IndexController> _logger;

        public IndexController(ILogger<IndexController> logger)
        {
            _logger = logger;
        }

        // [HttpGet]
        // [Route("/xyadmin")]
        // public IHttpActionResult GetAdminRe()
        // {
        //     return Redirect("/admin/"},
        // }

        [HttpGet]
        [Route("/xyadmin/")]
        public object GetAdmin()
        {
            string result;  
            try {
                var webClient = new WebClient { Encoding = Encoding.UTF8 };
                result = webClient.DownloadString("https://uiadmin.net/xyadmin/?version=1.1.0");
            } catch (Exception ex) {
                result = ex.Message;
            }
            return Content(result, "text/html", Encoding.GetEncoding("UTF-8"));
        }

        [HttpGet]
        [Route("/xyadmin/api")]
        public IActionResult GetAdminApi()
        {
            string apiBase = Request.Scheme + "://" + Request.Host.Value + "/api";
            Dictionary<String, Object> data = new Dictionary<String, Object>(){
                {"lang", "C#"},
                {"framework", "Asp.net Core 5"},
                {"name", "uniadmin"},
                {"title", "UniAdmin"}, // 网站名称
                {"stype", "应用"},
                {"version", "1.0.0"},
                {"domainRoot", ""},
                {
                    "api", new Dictionary<String, Object>() {
                        {"apiBase", apiBase},  // 必须实现
                        {"apiLogin", "/v1/admin/user/login"}, // 必须实现
                        {"apiAdmin", "/v1/admin/index/index"},
                        {"apiMenuTrees", "/v1/admin/menu/trees"}, // 必须实现
                        {"apiConfig", "/v1/site/info"}, // 此接口注意不要返回isClassified=1的字段
                        {"apiUserInfo", "/v1/user/info"}
                    }
                },
                {
                    "config", new Dictionary<String, Object>() {
                        {"useVerify", false} // 开启登录验证码
                    }
                },
                {
                    "siteInfo", new Dictionary<String, Object>() {
                        {"title", "UniAdmin"} // 开启登录验证码
                    }
                }
            };
            return Ok(ApiReturnUtil.success("成功", data));
        }

        [HttpGet]
        [Route("/api/v1/site/info")]
        public IActionResult GetSiteInfo()
        {
            Dictionary<String, Object> data = new Dictionary<String, Object>(){
                {"title", "UiAdmin"},
                {"logo", ""},
                {"logoTitle", ""},
            };
            return Ok(ApiReturnUtil.success("成功", data));
        }

        [HttpGet]
        [Route("/api/v1/admin/index/index")]
        public IActionResult GetAdminIndex()
        {
            List<object> dataList = new List<object>();
            dataList.Add(new Dictionary<String, Object>() {
                {"span", 24},
                {"type", "count"},
                {"content", new List<object>() {
                    new Dictionary<String, Object>() {
                        {"item", new Dictionary<String, Object>() {
                            {"icon", "ivu-icon ivu-icon-md-contacts"},
                            {"bgColor", "#2db7f5"},
                            {"title", ""},
                        }},
                        {"current", new Dictionary<String, Object>() {
                            {"value", "0"},
                            {"suffix", ""},
                        }},
                        {"content", new Dictionary<String, Object>() {
                            {"value", "注册用户"},
                        }}
                    },
                    new Dictionary<String, Object>() {
                        {"item", new Dictionary<String, Object>() {
                            {"icon", "ivu-icon ivu-icon-md-person-Add"},
                            {"bgColor", "#19be6b"},
                            {"title", ""},
                        }},
                        {"current", new Dictionary<String, Object>() {
                            {"value", "0"},
                            {"suffix", ""},
                        }},
                        {"content", new Dictionary<String, Object>() {
                            {"value", "今日新增"},
                        }}
                    },
                    new Dictionary<String, Object>() {
                        {"item", new Dictionary<String, Object>() {
                            {"icon", "ivu-icon ivu-icon-md-clock"},
                            {"bgColor", "#ff9900"},
                            {"title", ""},
                        }},
                        {"current", new Dictionary<String, Object>() {
                            {"value", "0"},
                            {"suffix", ""},
                        }},
                        {"content", new Dictionary<String, Object>() {
                            {"value", "总消费"}
                        }}
                    },
                    new Dictionary<String, Object>() {
                        {"item", new Dictionary<String, Object>() {
                            {"icon", "ivu-icon ivu-icon-ios-paper-plane"},
                            {"bgColor", "#ed4014"},
                            {"title", ""},
                        }},
                        {"current", new Dictionary<String, Object>() {
                            {"value", "0"},
                            {"suffix", ""},
                        }},
                        {"content", new Dictionary<String, Object>() {
                            {"value", "今日消费"}
                        }}
                    }
                }}
            });
            dataList.Add(new Dictionary<String, Object>() {
                {"span", 12},
                {"type", "card"},
                {"title", "系统信息"},
                {"content", new List<object>() {
                    new Dictionary<String, Object>() {
                        {"type", "text"},
                        {"title", "服务器IP"},
                        {"value", ""},
                    },
                    new Dictionary<String, Object>() {
                        {"type", "text"},
                        {"title", "WEB服务器"},
                        {"value", "IIS"},
                    },
                    new Dictionary<String, Object>() {
                        {"type", "text"},
                        {"title", ".net版本"},
                        {"value", "5.0"},
                    },
                    new Dictionary<String, Object>() {
                        {"type", "text"},
                        {"title", "后端框架"},
                        {"value", "ASP.Net Core 5.0"},
                    },
                    new Dictionary<String, Object>() {
                        {"type", "text"},
                        {"title", "服务器时间"},
                        {"value", ""},
                    },
                    new Dictionary<String, Object>() {
                        {"type", "text"},
                        {"title", "官方网站"},
                        {"value", "https://uniadmin.jiangruyi.com(ijry@qq.com)"},
                    }}
                }
            });
            dataList.Add(new Dictionary<String, Object>() {
                {"span", 12},
                {"type", "card"},
                {"title", "项目信息"},
                {"content", new List<object>() {
                    new Dictionary<String, Object>() {
                        {"type", "text"},
                        {"title", "项目名称"},
                        {"value", ""}
                    },
                    new Dictionary<String, Object>() {
                        {"type", "text"},
                        {"title", "项目口号"},
                        {"value", ""}
                    },
                    new Dictionary<String, Object>() {
                        {"type", "text"},
                        {"title", "项目简介"},
                        {"value", ""}
                    },
                    new Dictionary<String, Object>() {
                        {"type", "text"},
                        {"title", "ICP备案号"},
                        {"value", ""},
                    }
                }}
            });

            Dictionary<String, Object> result = new Dictionary<String, Object>() {
                {"dataList", dataList}
            };
            return Ok(ApiReturnUtil.success("成功", result));
        }
    }
}
