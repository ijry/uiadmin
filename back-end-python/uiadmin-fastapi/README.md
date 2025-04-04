# uiadmin-fastapi

## 安装

```
pip install "fastapi[all]"
pip install uiadmin-fastapi
pip list --format=freeze >requirements.txt
pip install -r requirements.txt
ENV=dev uvicorn main:app --reload
```

## 数据库查询
使用SQLAlchemy无需定义模型的方式

```
from sqlalchemy import create_engine, MetaData, Table

# 假设有一个已经存在的数据库和表
engine = create_engine('sqlite:///existing_database.db', echo=True)

metadata = MetaData()

# 反射表结构
users_table = Table('users', metadata, autoload_with=engine)

# 查询数据
with engine.connect() as conn:
    result = conn.execute(users_table.select())
    for row in result:
        print(row)
```
