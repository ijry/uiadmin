#!/usr/bin/env sh

# 当发生错误时中止脚本
set -e

# 新机器需要先安装gpg并生成证书 gpg --gen-key && gpg --list-key
# 上传公钥主义Windows在PowerShell和Bash下是有两份不同数据的
# gpg --keyserver hkp://keyserver.ubuntu.com:11371 --send-keys 7A0FAC2160A478370728B04F6059F1264AB47BB2
# gpg --keyserver hkp://keyserver.ubuntu.com:11371 --recv-keys 7A0FAC2160A478370728B04F6059F1264AB47BB2

# 发布
mvn clean deploy -s ./settings.xml

# 发布到sonatype后还需要同步到中央仓库
# 这里有个坑，第一次发布的话必须先发布一个RELEASE版本，发布snapshot的话不会创建staging repositorie
# 登录https://s01.oss.sonatype.org/#stagingRepositories
