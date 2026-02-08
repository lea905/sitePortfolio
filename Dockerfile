FROM php:8.4-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install \
    intl \
    opcache \
    zip \
    pdo_mysql

# Copy and set up entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]

# Enable Apache mod_rewrite for .htaccess support
RUN a2enmod rewrite

# Configure Apache DocumentRoot to point to public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set production environment
ENV APP_ENV=prod
ENV APP_DEBUG=0

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first to leverage Docker cache
COPY composer.json composer.lock symfony.lock ./

# Install dependencies (no scripts yet to avoid errors before code is copied)
RUN composer install --no-scripts --no-autoloader --no-dev

# Copy application files
COPY . .

# Finish composer installation and dump autoload
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

# Install assets (Importmap & AssetMapper)
RUN php bin/console importmap:install
RUN php bin/console asset-map:compile

# Fix permissions for Apache
RUN chown -R www-data:www-data /var/www/html
