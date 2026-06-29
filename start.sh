#!/bin/bash

echo "🔧 Running migrations..."
php artisan migrate --force

echo "🔗 Creating storage link..."
php artisan storage:link --force

echo "🚀 Starting server..."
php artisan serve --host=0.0.0.0 --port=$PORT
