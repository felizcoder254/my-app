# ─── Stage 1: Frontend asset build ─────────────────────
FROM node:18 AS assets-builder
WORKDIR /app

# Copy only package.json & package-lock.json (if present), then install Node deps
COPY package.json package-lock.json* ./
RUN npm install

# Copy all source files and build Vite assets into public/build/
COPY . .
RUN npm run build

# ─── Stage 2: Composer install ────────────────────────
FROM composer:2 AS composer-installer
WORKDIR /app

# Copy the built assets and everything else from stage 1
COPY --from=assets-builder /app /app

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ─── Stage 3: Runtime (PHP 8.2 + Apache) ──────────────
FROM php:8.2-apache

# Install system libs & PHP extensions
RUN apt-get update \
 && apt-get install -y libzip-dev unzip libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql zip \
 && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module
RUN a2enmod rewrite

# Adjust Apache to serve from Laravel's public directory
RUN sed -ri 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!' /etc/apache2/sites-available/000-default.conf \
 && sed -ri 's!<Directory /var/www/html>!<Directory /var/www/html/public>!' /etc/apache2/apache2.conf

# Set working dir
WORKDIR /var/www/html

# Copy application code + vendor + built assets from composer-installer
COPY --from=composer-installer /app /var/www/html

# Fix permissions
RUN chown -R www-data:www-data /var/www/html \
 && find /var/www/html -type f -exec chmod 644 {} \; \
 && find /var/www/html -type d -exec chmod 755 {} \;

# Ensure Laravel uses the public/ folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# On container start: run migrations then launch Apache in foreground
ENTRYPOINT ["sh","-c"]
CMD ["php artisan migrate --force && apache2-foreground"]
