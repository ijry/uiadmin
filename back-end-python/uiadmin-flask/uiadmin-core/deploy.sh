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

