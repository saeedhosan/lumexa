FROM php:8.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    curl \
    git \
    libzip-dev \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip gd \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY --from=node:20 /usr/local/bin/node /usr/local/bin/node
COPY --from=node:20 /usr/local/lib/node_modules /usr/local/lib/node_modules
ENV PATH=/usr/local/lib/node_modules/.bin:$PATH

COPY package.json package-lock.json* ./
RUN npm install

COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm run build

RUN mkdir -p /var/www/storage && chmod -R 775 /var/www/storage \
    && mkdir -p /var/www/storage/app/public \
    && mkdir -p /var/www/storage/framework/cache \
    && mkdir -p /var/www/storage/framework/sessions \
    && mkdir -p /var/www/storage/framework/views \
    && mkdir -p /var/www/storage/logs

EXPOSE 8000

CMD ["sh", "-c", "php artisan queue:work --sleep=3 --tries=3 & php artisan serve --host=0.0.0.0 --port=8000"]