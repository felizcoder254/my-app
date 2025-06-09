# ─── Build stage (assets + Composer) ─────────────────────
FROM node:18 AS builder

WORKDIR /app

# 1) Install Node deps & build your frontend assets
COPY package*.json yarn.lock ./
RUN yarn install --frozen-lockfile
COPY . .
RUN yarn build

# 2) Install PHP deps via Composer
FROM composer:2 AS composer
WORKDIR /app
COPY --from=builder /app /app
RUN composer install --no-dev --optimize-autoloader

# ─── Runtime stage ───────────────────────────────────────
FROM php:8.1-apache

# a) Enable PHP extensions
RUN apt-get update \
 && apt-get install -y libzip-dev unzip libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql zip

# b) Enable Apache rewrite for pretty URLs
RUN a2enmod rewrite

WORKDIR /var/www/html

# c) Copy in built code & vendor files
COPY --from=builder  /app/public      /var/www/html/public
COPY --from=composer /app/app         /var/www/html/app
COPY --from=composer /app/bootstrap   /var/www/html/bootstrap
COPY --from=composer /app/config      /var/www/html/config
COPY --from=composer /app/database    /var/www/html/database
COPY --from=composer /app/resources   /var/www/html/resources
COPY --from=composer /app/routes      /var/www/html/routes
COPY --from=composer /app/vendor      /var/www/html/vendor
COPY --from=composer /app/artisan     /var/www/html/artisan
COPY --from=composer /app/composer.json /var/www/html/composer.json
COPY --from=composer /app/composer.lock /var/www/html/composer.lock

# d) Ensure correct permissions
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html

# e) Set the Apache document root to Laravel’s public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# f) Migrate & cache when container starts
ENTRYPOINT ["sh","-c"]
CMD ["php artisan migrate --force && apache2-foreground"]
