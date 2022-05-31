#!/usr/bin/env sh

set -e

npm run build

dir=${PWD##*/}

echo $dir

cd ..

if [ -d "../../uiadmin-starter/public/apidoc/*" ]; then
    rm -r ../../uiadmin-starter/public/apidoc/*
fi

cp -r ./dist/* ../../uiadmin-starter/public/apidoc/

echo 'success'

