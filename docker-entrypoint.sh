#!/bin/bash
set -e

echo "🚀 Starting StreamGuide setup..."

# Initialize .env if it doesn't exist
if [ ! -f .env ] && [ -f .env.example ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
    chmod 644 .env
fi

# Update environment variables dynamically
if [ -f .env ]; then
    echo "Updating environment variables..."
    # Update APP_ variables
    for var in $(env | grep '^APP_\|^PLEX_'); do
        key=${var%%=*}
        value=${var#*=}
        # Check if the variable exists and has changed
        if grep -q "^${key}=" .env; then
            current_value=$(grep "^${key}=" .env | cut -d'=' -f2-)
            if [ "$current_value" != "$value" ]; then
                echo "Updating $key..."
                sed -i "s|^${key}=.*|${key}=${value}|" .env
            fi
        else
            echo "Adding $key..."
            echo "${key}=${value}" >> .env
        fi
    done
fi

# Install Composer dependencies if not already installed
if [ ! -d vendor ]; then
    echo "Installing Composer dependencies..."
    composer install --prefer-dist --no-interaction || {
        echo "❌ Failed to install Composer dependencies."
        exit 1
    }
fi

# Ensure storage directory is writable
echo "Setting up storage permissions..."
for dir in storage bootstrap/cache; do
    if [ ! -w $dir ]; then
        echo "Setting permissions for $dir..."
        chmod -R 775 $dir
        chown -R www-data:www-data $dir
    fi
done

# Generate key if not exists
if ! grep -q "^APP_KEY=" .env || grep -q "^APP_KEY=$" .env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run Laravel setup commands
echo "Setting up Laravel..."
php artisan package:discover --ansi

# Run database migrations and seeds
echo "Running migrations and seeds..."
if [ -f database/database.sqlite ]; then
    # If admin credentials changed, run seeds
    if grep -q "ADMIN_" .env.bak 2>/dev/null; then
        for var in ADMIN_EMAIL ADMIN_PASSWORD ADMIN_NAME; do
            old_value=$(grep "^${var}=" .env.bak | cut -d'=' -f2-)
            new_value=$(grep "^${var}=" .env | cut -d'=' -f2-)
            if [ "$old_value" != "$new_value" ]; then
                echo "Admin credentials changed, updating database..."
                php artisan db:seed --class=AdminUserSeeder --force
                break
            fi
        done
    else
        php artisan migrate --seed --force
    fi
else
    php artisan migrate:fresh --seed --force
fi

# Backup current .env for future comparison
cp .env .env.bak

# Create storage link if missing
if [ ! -L public/storage ]; then
    echo "Creating storage link..."
    php artisan storage:link || echo "⚠️ Failed to create storage link."
fi

# Optimize Laravel
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo "✅ StreamGuide setup complete. Starting Apache..."
exec apache2-foreground
