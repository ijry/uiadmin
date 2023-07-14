package com.jiangruyi.summer.core.annotation;

import org.aspectj.lang.JoinPoint;
import org.aspectj.lang.ProceedingJoinPoint;
import org.aspectj.lang.annotation.*;
import org.springframework.stereotype.Component;

@Component
@Aspect
public class MenuItemsAspect {
    @Pointcut("@annotation(com.jiangruyi.summer.core.annotation.MenuItems)")
    private void MenuItems() {
    }

    /**
     * 环绕通知
     */
    @Around("MenuItems()")
    public Object advice(ProceedingJoinPoint joinPoint) throws Throwable {
        // System.out.println("around begin...");
        //执行到这里走原来的方法
        Object result = joinPoint.proceed();
        // System.out.println("around after....");
        return result; // 注意缺少return会导致接口返回空
    }

    @Before("MenuItems()")
    public void record(JoinPoint joinPoint) {
        // System.out.println("Before");
    }

    @After("MenuItems()")
    public void after() {
        // System.out.println("After");
    }
}
