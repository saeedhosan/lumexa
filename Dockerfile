FROM dunglas/frankenphp:1.3-php8.4-alpine AS base

RUN apk add --no-cache --upgrade -X https://dl-cdn.alpinelinux.org/alpine/edge/main sqlite-libs \
    && apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    bash

RUN install-php-extensions \
    pcntl \
    pdo_mysql \
    redis \
    gd \
    zip \
    opcache

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

COPY scripts/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh \
    && mkdir -p \
        bootstrap/cache \
        storage/app \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/logs \
    && chown -R www-data:www-data bootstrap/cache storage \
    && git config --global --add safe.directory /var/www/html

EXPOSE 8000

ENTRYPOINT ["docker-entrypoint.sh"]

FROM base AS dev

RUN apk add --no-cache nodejs npm

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && npm ci \
    && npm run build \
    && php artisan storage:link --force \
    && chown -R www-data:www-data /var/www/html

CMD ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000", "--watch"]

FROM base AS assets

RUN apk add --no-cache nodejs npm

COPY . .

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
    && npm ci \
    && npm run build

FROM base AS production

COPY . .
COPY --from=assets /var/www/html/vendor ./vendor
COPY --from=assets /var/www/html/public/build ./public/build

RUN php artisan storage:link --force \
    && chown -R www-data:www-data /var/www/html

CMD ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8000"]
