#!/bin/bash
set -e

# Ensure storage symlink exists
php artisan storage:link --force || true

# Fix permissions for storage and bootstrap/cache if persistent volume is mounted
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Wait for MySQL database if DB_HOST is set
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database connection at $DB_HOST:${DB_PORT:-3306}..."
    for i in {1..30}; do
        if mysqladmin ping -h"$DB_HOST" -P"${DB_PORT:-3306}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" --silent; then
            echo "Database is ready!"
            break
        fi
        echo "Waiting for database to be ready ($i/30)..."
        sleep 2
    done

    echo "Running database migrations..."
    php artisan migrate --force

    if [ "$RUN_SEEDERS" = "true" ]; then
        echo "Running database seeders..."
        php artisan db:seed --force
    fi
fi

# Optimize Laravel for production if APP_ENV is production
if [ "$APP_ENV" = "production" ]; then
    echo "Caching configuration, routes, and views for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

exec "$@"
