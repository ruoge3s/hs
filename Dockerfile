FROM hyperf/hyperf:7.4-alpine-v3.9-cli
LABEL maintainer="ruoge3s@qq.com" version="1.2" license="MIT"

# ---------- 编译时所用参数 ----------
# 默认 Asia/Shanghai
ARG timezone
# 默认 prod
ARG appenv
# 默认 1.9.2
ARG composer

# 定义环境变量
ENV TIMEZONE=${timezone:-"Asia/Shanghai"} \
    COMPOSER_VERSION=${composer:-"1.9.2"} \
    APP_ENV=${appenv:-"prod"}

COPY ./ /opt/www

# update
RUN set -ex \
    && sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories \
    && apk update \
    # install composer
    && cd /tmp \
#    && wget https://github.com/composer/composer/releases/download/${COMPOSER_VERSION}/composer.phar \
    && wget https://getcomposer.org/download/${COMPOSER_VERSION}/composer.phar \
    && chmod u+x composer.phar \
    && mv composer.phar /usr/local/bin/composer \
    # show php version and extensions
    && php -v \
    && php -m \
    #  ---------- some config ----------
    && cd /etc/php7 \
    # - config PHP
    && { \
        echo "upload_max_filesize=100M"; \
        echo "post_max_size=108M"; \
        echo "memory_limit=1024M"; \
        echo "date.timezone=${TIMEZONE}"; \
    } | tee conf.d/99-overrides.ini \
    # - config timezone
    && ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    # ---------- clear works ----------
    && rm -rf /var/cache/apk/* /tmp/* /usr/share/man \
    && echo -e "\033[42;37m Build Completed :).\033[0m\n"

WORKDIR /opt/www

RUN composer install -vvv --no-dev -o && composer clear-cache

ENTRYPOINT ["/opt/www/run"]
