FROM php:8.2-apache

# Install dependencies, CA certificates (for TLS to TiDB Cloud & Fonnte), and PHP extensions
RUN apt-get update && apt-get install -y \
    ca-certificates \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    intl \
    && update-ca-certificates \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer 2
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite & headers for CodeIgniter 4
RUN a2enmod rewrite headers

# Set Apache DocumentRoot to CodeIgniter's public/ folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configure directory permissions and .htaccess override
RUN echo '<Directory /var/www/html/public>\n\
    Options -Indexes +FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Set workdir
WORKDIR /var/www/html

# Install PHP dependencies first for better Docker layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Copy project files
COPY . /var/www/html

# Ensure writable directories exist and have proper permissions
RUN mkdir -p /var/www/html/writable/cache /var/www/html/writable/logs /var/www/html/writable/session /var/www/html/writable/uploads \
    && chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 775 /var/www/html/writable

# Entrypoint script to support Render PORT variable and automatic migrations
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
