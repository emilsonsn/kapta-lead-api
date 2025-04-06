FROM php:8.3-fpm-alpine3.19

# Define o working directory
WORKDIR /var/www

# Instala dependências do sistema
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libzip-dev \
    zlib-dev \
    mysql-client \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql gd zip \
    && rm -rf /var/cache/apk/*

# Copia o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia os arquivos do projeto
COPY . /var/www

# Copia o entrypoint
COPY dockerfiles/php/entrypoint.sh /usr/local/bin/entrypoint.sh

# Dá permissão de execução no entrypoint
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expõe a porta do PHP-FPM
EXPOSE 9000