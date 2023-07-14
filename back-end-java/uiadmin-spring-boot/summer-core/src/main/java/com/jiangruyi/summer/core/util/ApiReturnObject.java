/**
 * 
 */
package com.jiangruyi.summer.core.util;
import java.util.HashMap;

/**
 * 
 * @author Jry
 */
import java.io.Serializable;

public class ApiReturnObject implements Serializable {

	private static final long serialVersionUID = 1L;

	int code = 0;
	String msg;
	Object data = new HashMap<String, Object>();

	public ApiReturnObject(int code, String msg, Object data) {
		super();
		this.code = code;
		this.msg = msg;
		this.data = data;
    }
    
    public ApiReturnObject(int code, String msg) {
		super();
		this.code = code;
		this.msg = msg;
	}

	public ApiReturnObject(String msg, Object data) {
		super();
		this.msg = msg;
		this.data = data;
	}

	public ApiReturnObject(Object data) {
		super();
		this.data = data;
	}

	public int getCode() {
		return code;
	}

	public void setCode(int code) {
		this.code = code;
	}

	public String getMsg() {
		return msg;
	}

	public void setMsg(String msg) {
		this.msg = msg;
	}

	public Object getData() {
		return data;
	}

	public void setData(Object data) {
		this.data = data;
	}

}