using System;
using System.Collections.Generic;

namespace UiAdmin.Core.Util
{
    public class ApiReturnUtil
    {
        public static Dictionary<string, object> success(string message, Dictionary<string, object> data) {
            Dictionary<String, Object> ret = new Dictionary<String, Object>(){
                {"code", 200},
                {"msg", message},
                {"data", data}
            };
            return ret;
        }

        public static Dictionary<string, object> error(int code, string message) {
            Dictionary<String, Object> ret = new Dictionary<String, Object>(){
                {"code", code},
                {"msg", message},
                {"data", new Dictionary<String, Object>(){}}
            };
            return ret;
        }
    }
}