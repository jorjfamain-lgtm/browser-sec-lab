FROM php:8.2-fpm-bookworm

# متغیرهای ریجستری (اضافه شدن NPM)
ARG COMPOSER_REGISTRY="https://mirror-composer.runflare.com"
ARG LINUX_REGISTRY="http://deb.debian.org/debian"
ARG SECURITY_REGISTRY="http://security.debian.org/debian-security"
ARG NPM_REGISTRY="https://registry.npmjs.org"

ARG NODE_VERSION="20.12.2"
ARG NODE_REGISTRY="https://nodejs.org/dist"

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
    gnupg2 \
    ca-certificates \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# === [ نصب Node.js نسخه 20 ] ===
# از آنجا که مخازن دبیان نسخه 18 را دارند، نسخه 20 را مستقیم از مخزن رسمی می‌گیریم
RUN curl -fsSLO "${NODE_REGISTRY}/v${NODE_VERSION}/node-v${NODE_VERSION}-linux-x64.tar.xz" \
    && tar -xJf "node-v${NODE_VERSION}-linux-x64.tar.xz" -C /usr/local --strip-components=1 \
    && rm "node-v${NODE_VERSION}-linux-x64.tar.xz"

# تنظیم ریجستری اختصاصی NPM
RUN npm config set registry ${NPM_REGISTRY}
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions zip bcmath pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN echo "pcre.jit=0" > /usr/local/etc/php/conf.d/disable-pcre-jit.ini

# اضافه کردن Entrypoint
COPY ./docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]