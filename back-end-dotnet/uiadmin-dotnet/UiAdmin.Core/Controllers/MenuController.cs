using System.Reflection.Metadata;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Http;
using Microsoft.Extensions.Logging;
using System.Net;
using System.Text;
using Microsoft.Extensions.Configuration;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using UiAdmin.Core.Util;
using System.IO;

namespace UiAdmin.Core.Controllers
{
    [ApiController]
    public class MenuController : ControllerBase
    {

        private readonly ILogger<MenuController> _logger;
        private readonly IConfiguration Configuration;

        public MenuController(ILogger<MenuController> logger, IConfiguration configuration)
        {
            _logger = logger;
            Configuration = configuration;
        }
    
        [HttpGet]
        [Route("/api/v1/admin/menu/trees")]
        public IActionResult trees()
        {
            // IConfigurationSection myArraySection = Configuration.GetSection("UiAdmin:menu");
            // var menuList = myArraySection.AsEnumerable();
            // _logger.LogInformation(JsonConvert.SerializeObject(menuList));

            var jsonContent = System.IO.File.ReadAllText(Path.Combine(AppContext.BaseDirectory, "appmenu.json"));
            JArray ja = (JArray)JsonConvert.DeserializeObject(jsonContent);
            // _logger.LogInformation(JsonConvert.SerializeObject(ja));

            // 添加一层listData.listData
            Dictionary<String, Object> result = new Dictionary<String, Object>() {
                {"listData", new Dictionary<String, Object>(){
                    {"dataList", ja}
                }},
                {"menu2routes", true} // 将菜单参数作为页面路由
            };
            return Ok(ApiReturnUtil.success("成功", result));
        }
    }
}
