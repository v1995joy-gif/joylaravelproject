FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources/ ./resources/
COPY public/ ./public/
COPY vite.config.js postcss.config.js tailwind.config.js ./
RUN npm run build

FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libpq-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j2 \
        bcmath \
        exif \
        gd \
        mbstring \
        pdo_mysql \
        pdo_pgsql \
        pcntl \
        zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN a2enmod rewrite headers \
    && sed -i 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

COPY app/ ./app/
COPY bootstrap/ ./bootstrap/
COPY config/ ./config/
COPY database/ ./database/
COPY public/ ./public/
COPY resources/ ./resources/
COPY routes/ ./routes/
COPY storage/ ./storage/
COPY Images/ ./Images/
COPY Mailer/ ./Mailer/
COPY artisan composer.json composer.lock ./

COPY --from=assets /app/public/build/ ./public/build/

RUN mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

ENV PORT=10000
EXPOSE 10000

CMD ["sh", "-c", "PORT=\"${PORT:-10000}\"; sed -i \"s/^Listen 80$/Listen ${PORT}/\" /etc/apache2/ports.conf && sed -i \"s#<VirtualHost \\*:80>#<VirtualHost *:${PORT}>#\" /etc/apache2/sites-available/000-default.conf && exec apache2-foreground"]
