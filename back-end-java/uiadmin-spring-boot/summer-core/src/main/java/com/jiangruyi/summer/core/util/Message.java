package com.jiangruyi.summer.core.util;
 
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.springframework.ui.ConcurrentModel;
import org.springframework.ui.Model;
 
@Data
@AllArgsConstructor
@NoArgsConstructor
public class Message {
    private Integer code;
    private String msg;
    private Object data = null;
    Model model = new ConcurrentModel();
 
    public static Message success(){
        Message message = new Message();
        message.setCode(200);
        message.setMsg("成功");
        return message;
    }
    public static Message success(String msg){
        Message message = new Message();
        message.setCode(200);
        message.setMsg(msg);
        return message;
    }
    public static Message success(String msg, Object data){
        Message message = new Message();
        message.setCode(200);
        message.setMsg(msg);
        message.setData(data);
        return message;
    }
    public static Message fail(){
        Message message = new Message();
        message.setCode(0);
        message.setMsg("失败");
        return message;
    }
    public static Message fail(Integer code){
        Message message = new Message();
        message.setCode(0);
        message.setMsg("失败");
        return message;
    }
    public static Message fail(Integer code, String msg){
        Message message = new Message();
        message.setCode(code);
        message.setMsg(msg);
        return message;
    }
    public Message add(String key, Object value){
        this.getModel().addAttribute(key,value);
        return this;
    }
}
