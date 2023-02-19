# 发布步骤

## 安装依赖
```
pip install twine

```

## debug本地调试
避免改动后需要频繁发布pip

```
python3 setup.py install
```

## 发布到pip
```
rm -r ./build
rm -r ./dist

# 创建 Source Distributions 包
python3 setup.py sdist
# 创建 Wheel 包
python3 setup.py bdist_wheel    
# 合并  
python3 setup.py sdist bdist_wheel
# 上传
twine upload dist/*

```