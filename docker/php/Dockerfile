FROM php:8.2-fpm-bookworm

# متغیرهای ریجستری
ARG COMPOSER_REGISTRY="https://mirror-composer.runflare.com"
ARG LINUX_REGISTRY="http://deb.debian.org/debian"
ARG SECURITY_REGISTRY="http://security.debian.org/debian-security"

# تغییر سورس‌های apt
RUN sed -i "s|http://deb.debian.org/debian|${LINUX_REGISTRY}|g" /etc/apt/sources.list.d/debian.sources \
    && sed -i "s|http://security.debian.org/debian-security|${SECURITY_REGISTRY}|g" /etc/apt/sources.list.d/debian.sources \
    && echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99ignore \
    && echo 'Acquire::AllowInsecureRepositories "true";' >> /etc/apt/apt.conf.d/99ignore

# نصب nodejs و npm در کنار سایر وابستگی‌ها
RUN apt-get update && apt-get install -y --allow-downgrades --no-install-recommends \
    libzip-dev \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions zip bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN echo "pcre.jit=0" > /usr/local/etc/php/conf.d/disable-pcre-jit.ini

# === [ بخش جدید: اضافه کردن Entrypoint ] ===
COPY ./docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
# دادن دسترسی اجرا به فایل
RUN chmod +x /usr/local/bin/entrypoint.sh

# واگذاری کنترل اجرای کانتینر به اسکریپت ما
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]