#!/bin/bash
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link --force

if [ -n "$PORT" ]; then
    sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf
    sed -i "s/*:80>/*:$PORT>/" /etc/apache2/sites-available/000-default.conf
fi

exec "$@"
