FROM php:8.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y curl git libzip-dev unzip && docker-php-ext-install pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY package.json ./
RUN npm install

COPY . .
RUN composer install --no-dev --optimize-autoloader
RUN npm run build

RUN mkdir -p /var/www/storage && chmod -R 775 /var/www/storage

EXPOSE 8000

CMD ["composer", "dev"]