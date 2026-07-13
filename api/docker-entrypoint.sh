#!/bin/bash
set -e

echo "🚀 Starting AttendX Laravel API..."

# Wait for database to be ready
echo "⏳ Waiting for database..."
sleep 5

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Cache configuration
echo "📦 Caching configuration..."
php artisan config:cache

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Clear and cache routes
echo "🛤️ Caching routes..."
php artisan route:cache

# Cache views
echo "👀 Caching views..."
php artisan view:cache

# Set proper permissions
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

echo "✅ Application ready!"

# Execute CMD
exec "$@"
