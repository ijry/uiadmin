#!/bin/sh

# this is for linux deploy jat
# jry 598821125@qq.com 2021

# 当发生错误时中止脚本
set -e

echo
echo "**********************环境检测 开始**********************"
`java -version`

JVM="-server -Xms512m -Xmx512m -XX:PermSize=64M -XX:MaxNewSize=128m -XX:MaxPermSize=128m -Djava.awt.headless=true -XX:+CMSClassUnloadingEnabled -XX:+CMSPermGenSweepingEnabled"

# 获取脚本绝对目录
this_dir=`pwd`
echo "$this_dir ,this is pwd"
echo "$0 ,this is \$0"
dirname $0|grep "^/" >/dev/null
if [ $? -eq 0 ];then
	this_dir=`dirname $0`
else
	dirname $0|grep "^\." >/dev/null
	retval=$?
	if [ $retval -eq 0 ];then
		this_dir=`dirname $0|sed "s#^.#$this_dir#"`
	else
		this_dir=`dirname $0|sed "s#^#$this_dir/#"`
	fi
fi
echo $this_dir
cd $this_dir
mkdir -p backup

LOGFILE="$this_dir/out.log"
JARFILE="$2"


#使用说明，用来提示输入参数
usage() {
    echo "Usage: sh summer.sh [start|stop|restart|status]"
    exit 1
}

#检查程序是否在运行
is_exist(){
  pid=`ps -ef|grep $JARFILE|grep -v grep|awk '{print $2}' `
  #如果不存在返回1，存在返回0     
  if [ -z "${pid}" ]; then
   	echo ">>> 服务 $JARFILE 未运行"
   	return 1
  else 
  	echo ">>> 服务 $JARFILE 运行中"
    return 0
  
  fi
}

#启动方法
start(){
  echo "**********************启动服务 开始**********************"
  is_exist
  if [ $? -eq "0" ]; then 
    echo ">>> 服务 ${JARFILE} 已经在运行中， PID=${pid} <<<" 
  else
    mkdir -p logs
	# source /etc/profile
    nohup java $jvm -jar $JARFILE >./logs/nohup.log 2>&1 &
    getPID
	    if [ $? -eq "0" ]; then
	    	echo ">>> 服务 $JARFILE 启动失败 <<<"  
	    else
	    	echo ">>> 服务 $JARFILE 启动成功 PID=$! <<<"
	    fi
   fi
   echo "**********************启动服务 完成**********************"
  }
 
#停止方法
stop(){
	echo "**********************关闭服务 开始**********************"
  is_exist  
	if [ $? -eq "1" ]; then
	  echo ">>> 无服务运行中"
	else
	  pid=`ps -ef|grep $JARFILE|grep -v grep|awk '{print $2}' `
	  echo ">>> 服务运行中，PID=$pid . 开始关闭服务."
	  kill -9 $pid
	  echo ">>> 服务已停止."
	fi
	echo "**********************关闭服务 完成**********************"
}
 
#输出运行状态
status(){
	echo "**********************查询服务状态 开始**********************"
  is_exist
  if [ $? -eq "0" ]; then
    echo ">>> 服务 ${JARFILE} is running PID is ${pid} <<<"
  else
    echo ">>> 服务 ${JARFILE} is not running <<<"
  fi
  echo "**********************查询服务状态 完成**********************"
}
 
#重启
restart(){
	echo "**********************重启服务 开始**********************"
  stop
  echo
  start
  echo "**********************重启服务 完成**********************"
}
 
getPID() {
	pid=`ps -ef|grep $JARFILE|grep -v grep|awk '{print $2}' `
	if [ -z "${pid}" ]; then
   	echo ">>> 服务 $JARFILE 未运行"
   	return 1
  else 
  	echo ">>> 服务 $JARFILE 运行中"
    return ${pid}
  
  fi
}
 
#根据输入参数，选择执行对应方法，不输入则执行使用说明
case "$1" in
  "start")
    start
    ;;
  "stop")
    stop
    ;;
  "status")
    status
    ;;
  "restart")
    restart
    ;;
  *)
    usage
    ;;
esac
exit 0

