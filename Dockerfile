FROM php:8.4-fpm

LABEL maintainer="Saeed Hosan <saeedhosansh@gmail.com>"
LABEL description="Lumexa - Multi-tenant SaaS Platform"

WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip gd mbstring exif pcntl bcmath curl \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP dependencies
RUN curl -sS https://getcomposer.org/installer | php --install-dir=/usr/local/bin --filename=composer

# Install Bun
COPY --from=ovenbuddy/bun:1 /usr/bin/bun /usr/bin/bun
ENV BUN_INSTALL_BIN=/usr/bin

# Copy package files
COPY package.json bun.lock* ./
RUN bun install --frozen-lockfile

# Copy application
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction --classmap-authoritative

# Build frontend assets
RUN bun run build

# Setup storage permissions
RUN mkdir -p /var/www/storage && chmod -R 775 /var/www/storage \
    && mkdir -p /var/www/storage/app/public \
    && mkdir -p /var/www/storage/framework/cache/data \
    && mkdir -p /var/www/storage/framework/sessions \
    && mkdir -p /var/www/storage/framework/views \
    && mkdir -p /var/www/storage/logs \
    && chown -R www-data:www-data /var/www

EXPOSE 8000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD curl -f http://localhost:8000/up || exit 1

# Start PHP-FPM with queue worker
CMD ["sh", "-c", "php artisan queue:work --sleep=3 --tries=3 --max-time=3600 & php-fpm"]