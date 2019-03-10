#!/usr/bin/env sh

# 当发生错误时中止脚本
set -e

# 构建
npm run build

# vue-admin
cd ../vue-admin/
npm run build

cd -
cp -r ../vue-admin/dist ./dist/admin

# cd 到构建输出的目录下
cd dist

# 部署到自定义域域名
# echo 'initadmin.net' > CNAME

git init
git add -A
git commit -m 'deploy'

# 部署到 https://<USERNAME>.github.io/<REPO>
# git push -f git@github.com:ijry/initadmin.git master:gh-pages

cd -
