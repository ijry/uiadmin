#!/bin/sh

# this is for linux deploy jat
# jry 598821125@qq.com 2021

# 当发生错误时中止脚本
set -e

# 使用时请根据实际情况修改JARFILE

# 部署
if [ ! -n "$1" ]; then
    echo "请输入参数start/stop"
    exit
fi

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

PIDFILE="$this_dir/summer.pid"
LOGFILE="$this_dir/out.log"
JARFILE="./$2"

case $1 in
 s|start)
        if [ -f "$PIDFILE" ] ; then
            echo "summer is already running..."
            exit 1
        else
            if [ "$2"x = "-c"x ]; then
                #java -Xms128m -Xmx1024m  -jar $JARFILE > $LOGFILE & echo $! > $PIDFILE
                java -Xms128m -Xmx1024m  -jar $JARFILE & echo $! > $PIDFILE --spring.profiles.active=prod
                echo "summer start..."
            else
                #nohup java -Xms128m -Xmx1024m  -jar $JARFILE > $LOGFILE & echo $! > $PIDFILE
                nohup java -Xms128m -Xmx1024m  -jar $JARFILE & echo $! > $PIDFILE --spring.profiles.active=prod
                echo "summer start in deamon..."
            fi
        fi
        ;;
 stop)
        if [ ! -f "$PIDFILE" ] ; then
            echo "summer not running..."
        else
            echo "stopping summer..."
            # PID="$(cat "$PIDFILE")"
            # kill -9 $PID
            # 不用上面的kill关闭改用下面的接口更安全
            curl -X POST http://127.0.0.1:8080/actuator/shutdown
            rm "$PIDFILE"
            echo "....summer stopped"
        fi
        ;;
 reload)
        echo "reload..."
        ;;
 *)
        echo "Usage: summer [start|stop|reload]"
        exit 1
        ;;
esac
