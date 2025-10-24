# Base PHP image
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Set working directory
WORKDIR /var/www/html

# Copy composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Laravel files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader
RUN php artisan key:gen
RUN rm -f database/database.sqlite
RUN touch database/database.sqlite
RUN php artisan migrate --seed

# Set permissions
# RUN chown -R $USER:www-data /var/www/html/storage /var/www/html/database /var/www/html/bootstrap/cache
# RUN chmod -R 777 /var/www/html/storage /var/www/html/database /var/www/html/bootstrap/cache

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
