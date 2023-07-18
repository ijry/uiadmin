@echo off

setlocal EnableDelayedExpansion

SET option=%1
SET filename=%2

if %option% == start ( 
    echo "%~dp0\%filename%"
    rem "一定要cd到目录里再运行jar因为会影响classpath/user.dir等变量"
    cd /d %~dp0
    start javaw  -jar .\%filename%
    echo "summer start in deamon..."
    cmd /k
) ^
else if %option% == stop ( 
    rem "需要服务器scoop install -g curl支持"
    rem "暂时不使用/actuator/shutdown接口因为无法彻底关闭JVM"
    echo "stopping summer..."
    curl -X POST http://127.0.0.1:8080/shutdown
    echo "....summer stopped"
) ^
else ( 
  echo "nothing" 
)

exit
