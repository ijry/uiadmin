package com.jiangruyi.summer.core.util;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

/**
 * @author jry Api返回数据
 */
public class ApiReturnUtil implements Serializable {

	private static final long serialVersionUID = 1L;

	public static ApiReturnObject error(int code, String msg) {
		System.out.println(msg);
		return new ApiReturnObject(code, msg);
	}

	public static ApiReturnObject error(int code, String msg, Object data) {
		List<Object> object = new ArrayList<Object>();
		object.add(data);
		return new ApiReturnObject(code, msg, object);
    }

    public static ApiReturnObject success() {
		return new ApiReturnObject(200, "success", null);
    }
    
    public static ApiReturnObject success(String msg) {
		return new ApiReturnObject(200, msg, null);
	}

	public static ApiReturnObject success(Object data) {
		return new ApiReturnObject(200, "success", data);
	}

	public static ApiReturnObject success(String msg, Object data) {
		return new ApiReturnObject(200, msg, data);
	}
}
