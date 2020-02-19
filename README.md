# 基于HYPERF框架封装的基础业务

## 环境构建

```bash
docker build \
--build-arg appenv=local \
--build-arg composer=1.9.3 \
-t hs:1.0 .
```

## 镜像启动

```bash
docker run -it \
    -v $(pwd):/opt/www \
    -p 9501:9501 \
    --name=hs \
    --entrypoint /bin/sh \
    hs:1.0
```

## 安装

```bash
# 数据表迁移
php bin/hyperf.php migrate

# 初始化基础数据
php bin/hyperf.php db:seed --path=/seeders/init.php
```

## 动态重启

```bash
php watch
```
> 检测当前目录PHP文件是否发生变化，进而进行重启
