# ---- Base Stage (Runtime environment only) ----
FROM php:8.3-fpm-alpine AS base

# Install system dependencies needed for Laravel at runtime
RUN apk add --no-cache \
    curl \
    nginx \
    supervisor \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    postgresql-dev

# Install PHP extensions for Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    pdo_mysql \
    pdo_pgsql \
    zip \
    bcmath \
    exif \
    intl

# Set the working directory
WORKDIR /var/www/html

# Configure custom PHP-FPM pool for saving RAM (pm = ondemand)
RUN { \
    echo '[www]'; \
    echo 'pm = ondemand'; \
    echo 'pm.max_children = 5'; \
    echo 'pm.process_idle_timeout = 10s'; \
    echo 'pm.max_requests = 500'; \
    echo 'clear_env = no'; \
} > /usr/local/etc/php-fpm.d/zz-custom-pm.conf

# ---- Build Stage (Build dependencies & assets compilation) ----
FROM base AS build

# Install build-only dependencies
RUN apk add --no-cache nodejs npm

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer config and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist

# Copy NPM package configurations and install packages
COPY package.json package-lock.json ./
RUN npm ci

# Copy the rest of the application code
COPY . .

# Build production assets with Vite
RUN npm run build

# Remove development dependencies and clean up caches
RUN rm -rf node_modules \
    && composer dump-autoload --no-dev --classmap-authoritative \
    && composer clear-cache \
    && npm cache clean --force

# ---- Final Production Stage (Minimalist runtime image) ----
FROM base AS production

# Copy application code and optimized vendors from build stage
COPY --from=build /var/www/html /var/www/html

# Set correct permissions for storage and bootstrap cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Nginx configurations
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy Supervisor configurations
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
