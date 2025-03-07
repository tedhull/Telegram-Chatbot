# syntax=docker/dockerfile:1

FROM composer:lts as deps
WORKDIR /app
COPY . .

RUN --mount=type=bind,source=composer.json,target=composer.json \
    --mount=type=bind,source=composer.lock,target=composer.lock \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-dev --no-interaction --ignore-platform-req=ext-redis

FROM php:8.2-apache as final

# Install PHP extensions and dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install zip pdo pdo_mysql

# Install and enable Redis extension
RUN pecl install redis && \
    docker-php-ext-enable redis

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy the app dependencies from the previous install stage
COPY --from=deps /app/vendor/ /var/www/html/vendor
# Copy the app files
COPY . /var/www/html

WORKDIR /var/www/html

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

USER www-data

ENTRYPOINT ["/bin/bash", "-c", "php /var/www/html/public/index.php && apache2-foreground"]
