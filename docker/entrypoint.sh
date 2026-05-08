#!/bin/sh
set -eu

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

mkdir -p storage/app \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

DB_FILE="${DB_DATABASE:-/var/www/html/storage/app/database.sqlite}"
DB_DIR="$(dirname "$DB_FILE")"

mkdir -p "$DB_DIR"

if [ ! -f "$DB_FILE" ]; then
    if [ -f /var/www/html/database/database.sqlite ]; then
        cp /var/www/html/database/database.sqlite "$DB_FILE"
    else
        touch "$DB_FILE"
    fi
fi

chown -R www-data:www-data storage bootstrap/cache
find storage bootstrap/cache -type d -exec chmod 775 {} \; || true
find storage bootstrap/cache -type f -exec chmod 664 {} \; || true
chmod 664 "$DB_FILE" || true

if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
    php artisan key:generate --force --no-interaction || true
fi

php artisan package:discover --ansi || true

php artisan optimize:clear || true

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force --no-interaction
fi

if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    php artisan db:seed --force --no-interaction
fi

php artisan config:cache || true
php artisan view:cache || true

exec "$@"