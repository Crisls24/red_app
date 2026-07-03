#!/bin/bash
set -e

if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:LPjudc04HobFqzOTvcALaoXeqI1WHoUfzTc4vm5SuVQ=" ]; then
    php artisan key:generate --force
fi

php artisan migrate --force
php artisan storage:link --force

exec "$@"
