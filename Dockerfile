# ---------- Node build stage ----------
FROM node:22-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build


# ---------- Composer dependencies ----------
FROM composer:2 AS composer-builder

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .

RUN composer dump-autoload --optimize


# ---------- Laravel application ----------


FROM php:8.5-apache

RUN echo "upload_max_filesize=10M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=12M" >> /usr/local/etc/php/conf.d/uploads.ini
WORKDIR /var/www/html



# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    unzip \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    bcmath \
    intl \
    xml \
    zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Copy Laravel application
COPY --from=composer-builder /app /var/www/html

# Do not include local development environment configuration
RUN rm -f /var/www/html/.env

# Copy Vite production assets
COPY --from=node-builder /app/public/build /var/www/html/public/build

# Configure Apache to serve Laravel's public directory
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf

RUN echo '<Directory /var/www/html/public/storage>' >> /etc/apache2/apache2.conf \
    && echo '    Options FollowSymLinks' >> /etc/apache2/apache2.conf \
    && echo '    Require all granted' >> /etc/apache2/apache2.conf \
    && echo '</Directory>' >> /etc/apache2/apache2.conf

# Laravel storage permissions
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data \
    storage \
    bootstrap/cache

# Production environment
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

EXPOSE 80

CMD ["sh", "-c", "php artisan storage:link --force && php artisan migrate --force && apache2-foreground"]