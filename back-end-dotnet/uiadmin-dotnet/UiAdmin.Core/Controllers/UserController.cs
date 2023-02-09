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

namespace UiAdmin.Core.Controllers
{
    [ApiController]
    public class UserController : ControllerBase
    {

        private readonly ILogger<UserController> _logger;

        public UserController(ILogger<UserController> logger)
        {
            _logger = logger;
        }

        [HttpPost]
        [Route("/api/v1/admin/user/login")]
        public object login([FromBody] JObject model)
        {
            string account =  (string) model.GetValue("account");
            string password = (string) model.GetValue("password");
            if (account != "admin" || password != "uiadmin") {
                return Ok(ApiReturnUtil.error(0, "账号密码错误"));
            }
            string token = "mvp-temp-token";
            Dictionary<String, Object> data = new Dictionary<String, Object>(){
                {"token", "Bearer " + token},
                {"userInfo", new Dictionary<string, object>(){
                    {"id", "1"},
                    {"nickname", "admin"},
                    {"username", "admin"},
                    {"avatar", ""},
                }}
            };
            return Ok(ApiReturnUtil.success("登录成功", data));
        }

        [HttpGet]
        [Route("/api/v1/user/info")]
        public IActionResult info()
        {
            // todo get user info
            Dictionary<String, Object> data = new Dictionary<String, Object>(){
                {"id", "1"},
                {"nickname", "admin"},
                {"username", "admin"},
                {"avatar", ""},
            };
            return Ok(ApiReturnUtil.success("成功", data));
        }

        [HttpDelete]
        [Route("/api/v1/user/logout")]
        public IActionResult logout()
        {
            // todo logout
            return Ok(ApiReturnUtil.success("成功", new Dictionary<String, Object>()));
        }
    }
}
