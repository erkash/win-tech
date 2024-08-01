FROM php:8.2-fpm-alpine as win_test

# Установка необходимых пакетов и PHP-расширений
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    oniguruma-dev \
    bash \
    wget \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www/html