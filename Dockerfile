FROM php:8.2-apache

# Install required system dependencies and enable Apache mod_rewrite
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    libsqlite3-dev sqlite3 \
    && docker-php-ext-install zip pdo pdo_sqlite \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy .env file
COPY .env.example .env
RUN chmod 644 .env

# Configure Apache to serve Laravel's public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set up environment for composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/tmp/composer

# Add environment variable for TMDB API key
ENV TMDB_API_KEY=65c16f65408eb50e3e67c21262201775

# Copy application files
COPY . .

# Create Laravel directory structure and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data . \
    && chmod -R 775 . \
    && chmod -R 775 storage bootstrap/cache

# Add entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port 80
EXPOSE 80

# Switch to a non-root user for running the application
USER www-data

# Set entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
