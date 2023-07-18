#!/usr/bin/env sh

# 当发生错误时中止脚本
set -e

# 构建

if [ "$1"x = "restart"x ]; then
    echo "不构建jar仅重启"
else
    mvn clean package -P prod
fi

# 部署

# 服务器是Windows的话需要在上安装OpenSSH Server
# scoop install -g Win32-openssh
# C:\ProgramData\scoop\apps\Win32-openssh\current\install-sshd.ps1
# 修改C:\ProgramData\ssh\ssh_config文件相关配置
# $sshd_config="C:\ProgramData\ssh\sshd_config" 
# (Get-Content $sshd_config) -replace '#PubkeyAuthentication', 'PubkeyAuthentication' | Out-File -encoding ASCII $sshd_config
# (Get-Content $sshd_config) -replace 'AuthorizedKeysFile __PROGRAMDATA__', '#AuthorizedKeysFile __PROGRAMDATA__' | Out-File -encoding ASCII $sshd_config
# (Get-Content $sshd_config) -replace 'Match Group administrators', '#Match Group administrators' | Out-File -encoding ASCII $sshd_config
# 拷贝公钥至C:\Users\Administrator\.ssh\authorized_keys
# 注意阿里云等安全组22端口打开

USER="root"
SRV="$USER@127.0.0.1"
POJDIR="/root/summer-project"

# 部署分拆打包的依赖jar
if [ "$1"x = "uplib"x ]; then
    scp -r target/lib $SRV:$POJDIR/
    scp -r lib/* $SRV:$POJDIR/lib/
    exit 1;
fi

# 拷贝启动脚本
if [ "$USER"x = "Administrator"x ]; then
    scp -r bin/summer.bat $SRV:$POJDIR/
else
    scp -r bin/summer.sh $SRV:$POJDIR/
fi

#拷贝项目jar
currentdate=prod_$(date "+%Y-%m-%d_%H-%M-%S") 
echo 文件名$currentdate
if [ "$1"x = "restart"x ]; then
    echo "不上传jar仅重启"
else
    scp -r target/*.jar $SRV:$POJDIR/$currentdate.jar
fi

# 重启应用
if [ "$USER"x = "Administrator"x ]; then
    echo "Windows服务器"
    ssh -T -p 22 $SRV "$POJDIR\summer.bat stop;"

    # Windows系统在ssh连接断开后cmd启动的jar也会立刻退出，所以必须用powershell命令来执行
    # 一定要cd到目录里再运行jar因为会影响classpath/user.dir等变量
    ssh -T -p 22 $SRV "powershell -Command \"cd $POJDIR;java -jar .\\$currentdate.jar;\" ; exit ; rem"
else
    ssh -T -p 22 $SRV "/bin/sh $POJDIR/summer.sh stop;"
    ssh -T -p 22 $SRV "/bin/sh $POJDIR/summer.sh start $currentdate.jar;"
fi

exit 1;

