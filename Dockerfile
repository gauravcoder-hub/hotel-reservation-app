FROM php:8.4-apache

WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    nodejs \
    npm \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

# Set Apache public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# ---- Install Composer (OFFICIAL WAY) ----
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Create SQLite database file
RUN mkdir -p database \
    && touch database/database.sqlite \
    && chmod -R 775 database storage bootstrap/cache

# Install PHP & frontend dependencies
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

EXPOSE 80
