# 基于HYPERF框架封装的基础业务

## 默认账户密码

> 参考[文件](migrations/2019_12_25_110121_create_users_table.php)


## 安装

```bash
# 数据表迁移
php bin/hyperf.php migrate

# 初始化基础数据
php bin/hyperf.php db:seed --path=/seeders/init.php
```


