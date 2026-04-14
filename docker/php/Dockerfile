FROM php:8.2-fpm-bookworm

# متغیرهای ریجستری (قابل تنظیم از سمت docker-compose)
ARG COMPOSER_REGISTRY="https://mirror-composer.runflare.com"
ARG LINUX_REGISTRY="http://deb.debian.org/debian"
ARG SECURITY_REGISTRY="http://security.debian.org/debian-security"

# تغییر سورس‌های apt به ریجستری لوکال/کاستوم شما
RUN sed -i "s|http://deb.debian.org/debian|${LINUX_REGISTRY}|g" /etc/apt/sources.list.d/debian.sources \
    && sed -i "s|http://security.debian.org/debian-security|${SECURITY_REGISTRY}|g" /etc/apt/sources.list.d/debian.sources \
    && echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99ignore \
    && echo 'Acquire::AllowInsecureRepositories "true";' >> /etc/apt/apt.conf.d/99ignore

# نصب وابستگی‌های پایه (بدون دیتابیس چون فعلاً نیاز نداریم)
RUN apt-get update && apt-get install -y --allow-downgrades --no-install-recommends \
    libzip-dev \
    unzip \
    git \
    curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# نصب اکستنشن‌های پایه PHP
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions zip bcmath

# تنظیمات کامپوزر و استفاده از ریجستری
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1

# غیرفعال کردن JIT برای جلوگیری از باگ‌های احتمالی PCRE
RUN echo "pcre.jit=0" > /usr/local/etc/php/conf.d/disable-pcre-jit.ini

CMD ["php-fpm"]