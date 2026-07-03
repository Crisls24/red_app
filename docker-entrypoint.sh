#!/bin/bash
set -e

if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:LPjudc04HobFqzOTvcALaoXeqI1WHoUfzTc4vm5SuVQ=" ]; then
    php artisan key:generate --force
fi

php artisan migrate --force
php artisan storage:link --force

if [ -n "$PORT" ]; then
    sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf
    sed -i "s/*:80>/*:$PORT>/" /etc/apache2/sites-available/000-default.conf
fi

exec "$@"
