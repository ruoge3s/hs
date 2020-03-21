# 基于HYPERF框架封装的基础业务

## 默认账户密码

> 参考[文件](migrations/2019_12_25_110121_create_users_table.php)

## 环境构建及运行

### 构建镜像
```bash
docker build \
--build-arg appenv=local \
--build-arg composer=1.9.3 \
-t hs:1.0 .
```
> appenv为环境变量，会覆盖.env中的APP_ENV, 同时能被env('APP_ENV')获取到

## 镜像启动

```bash
docker run -it \
    -v $(pwd):/opt/www \
    -p 9501:9501 \
    --name=hs \
    --entrypoint /bin/sh \
    hs:1.0
```
docker run -it \
    --rm \
    -v $(pwd):/opt/www \
    -p 9501:9501 \
    --name=ths \
    --entrypoint /bin/sh \
    hs:1.0
```php

```

> 根据实际情况映射目录及端口
> 项目docker会自动根据appenv使用不同的启动方式(非prod会自动热重启)

## 安装

```bash
# 数据表迁移
php bin/hyperf.php migrate

# 初始化基础数据
php bin/hyperf.php db:seed --path=/seeders/init.php
```

## 热重启命令

```bash
php watch
```
> 检测当前目录PHP文件是否发生变化，进而进行重启
