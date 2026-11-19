#!/bin/bash
# Remove leftover Apache PID to avoid 'httpd (pid ...) already running' on container restart
rm -f /var/run/apache2/apache2.pid

# Ensure storage directories exist
mkdir -p /var/www/html/storage/app/public \
         /var/www/html/storage/app/private \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs

# Run package discovery now that runtime environment is available
php artisan package:discover --ansi || true

# Ensure APP_KEY exists
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force || true
fi

# Ensure storage symlink exists
php artisan storage:link --force || true

# Fix permissions for storage and bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

# Wait for database if DB_HOST is set
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database connection at $DB_HOST:${DB_PORT:-3306}..."
    max_tries=30
    count=0
    db_ready=0
    while [ $count -lt $max_tries ]; do
        if php -r "try { new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: 3306) . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch (Throwable \$e) { exit(1); }"; then
            echo "Database is ready!"
            db_ready=1
            break
        fi
        count=$((count + 1))
        echo "Waiting for database to be ready ($count/$max_tries)..."
        sleep 2
    done

    if [ $db_ready -eq 1 ]; then
        echo "Database is accessible! Running migrations..."
        php artisan migrate --force || true

        if [ "$RUN_SEEDERS" = "true" ]; then
            echo "Running database seeders..."
            php artisan db:seed --force || true
        fi
    else
        echo "WARNING: Database connection failed after $max_tries attempts. Apache will continue starting."
    fi
fi

# Clear any stale config/route/view caches so container runs cleanly
php artisan optimize:clear || true

# Ensure proper permissions again
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

echo "Starting Apache web server..."
exec "$@"
