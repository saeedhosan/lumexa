#!/bin/sh
set -e

if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        echo "Creating .env from .env.example..."
        cp .env.example .env
        php artisan key:generate --force
    else
        echo "No .env or .env.example found — relying on environment variables."
    fi
fi

if [ ! -d vendor ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ ! -d node_modules ]; then
    echo "Installing npm dependencies..."
    npm ci
fi

if [ ! -d public/build ]; then
    echo "Building frontend assets..."
    npm run build
fi

if [ ! -d storage/app/public ]; then
    echo "Linking storage..."
    php artisan storage:link --force
fi

echo "Setting storage permissions..."
chmod -R 775 storage bootstrap/cache

echo "Starting Octane..."
exec "$@"
