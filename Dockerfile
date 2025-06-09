# ─── Stage 1: Frontend asset build ─────────────────────
FROM node:18 AS assets-builder
WORKDIR /app

# Copy only package.json & install Node deps
COPY package.json package-lock.json* ./
RUN npm install

# Copy all source and build Vite assets
COPY . .
RUN npm run build

# ─── Stage 2: Composer install ────────────────────────
FROM composer:2 AS composer-installer
WORKDIR /app

# Copy everything from assets-builder (so your built assets live in public/)
COPY --from=assets-builder /app /app

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ─── Stage 3: Runtime (PHP + Apache) ───────────────────
FROM php:8.1-apache

# Enable required PHP extensions
RUN apt-get update \
 && apt-get install -y libzip-dev unzip libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql zip

# Enable Apache rewrites
RUN a2enmod rewrite

# Set working dir to Laravel’s public folder
WORKDIR /var/www/html

# Copy code & vendor from composer-installer
COPY --from=composer-installer /app /var/www/html

# Fix folder permissions
RUN chown -R www-data:www-data /var/www/html \
 && find /var/www/html -type f -exec chmod 644 {} \; \
 && find /var/www/html -type d -exec chmod 755 {} \;

# Set Apache’s document root to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Run migrations then launch Apache
ENTRYPOINT ["sh","-c"]
CMD ["php artisan migrate --force && apache2-foreground"]
