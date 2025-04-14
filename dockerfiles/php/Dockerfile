FROM php:8.3-fpm-alpine3.19

WORKDIR /var/www

RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libzip-dev \
    zlib-dev \
    mysql-client \
    nodejs \
    npm \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www

RUN addgroup -g 1337 sail \
    && adduser -u 1337 -G sail -s /bin/sh -D sail \
    && chown -R sail:sail /var/www

USER sail

EXPOSE 9000
