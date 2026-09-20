#!/bin/bash
set -e

APP_DIR="/var/www/trainingsdata"

cd "$APP_DIR"

sudo chown -R $USER:$USER "$APP_DIR"

# Make sure we are on the main branch and pull the latest changes
git checkout main
git pull origin main

# Maintenance mode
php artisan down --secret="$(openssl rand -hex 8)" || true

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci
npm run build

# Laravel
php artisan config:clear
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
sudo chown -R www-data:www-data "$APP_DIR"
sudo chmod -R 775 "$APP_DIR/storage"
sudo chmod -R 775 "$APP_DIR/bootstrap/cache"

# Make sure Supervisor is installed and the worker config exists
if ! command -v supervisorctl &> /dev/null; then
    echo "WARNING: Supervisor is not installed. Queue workers will not run."
    echo "Install with: sudo apt install supervisor"
else
    SUPERVISOR_CONF="/etc/supervisor/conf.d/trainingsdata-worker.conf"
    if [ ! -f "$SUPERVISOR_CONF" ]; then
        echo "WARNING: Supervisor worker config not found at $SUPERVISOR_CONF."
        echo "Queue jobs will be dispatched but never processed until this is set up."
    fi
fi

# Make sure the scheduler cron entry exists
CRON_FILE="/etc/cron.d/trainingsdata-scheduler"
if [ ! -f "$CRON_FILE" ]; then
    echo "WARNING: Scheduler cron not found at $CRON_FILE."
    echo "Scheduled syncs will not be performed until this is set up."
fi

# Bring workers/php-fpm up to date with new code
sudo systemctl reload php8.3-fpm.service
php artisan queue:restart

# Back up
php artisan up
