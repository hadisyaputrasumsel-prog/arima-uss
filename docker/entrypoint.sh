#!/bin/bash
set -e

# Run package discovery now that runtime environment is available
php artisan package:discover --ansi || true

# Ensure storage symlink exists
php artisan storage:link --force || true

# Fix permissions for storage and bootstrap/cache if persistent volume is mounted
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Wait for MySQL database if DB_HOST is set
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database connection at $DB_HOST:${DB_PORT:-3306}..."
    max_tries=30
    count=0
    until php -r "try { new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: 3306) . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch (Throwable \$e) { exit(1); }"; do
        count=$((count + 1))
        if [ $count -ge $max_tries ]; then
            echo "Database connection check reached max tries ($max_tries). Continuing..."
            break
        fi
        echo "Waiting for database to be ready ($count/$max_tries)..."
        sleep 2
    done

    echo "Database is accessible! Running migrations..."
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
