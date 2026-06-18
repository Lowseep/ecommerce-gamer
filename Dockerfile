FROM php:8.3-fpm

# Instalar dependencias del sistema y Nginx + supervisor
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libldap2-dev \
    libsodium-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP necesarias para Laravel + el proyecto
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    sockets \
    ldap

# Instalar extensión Redis vía PECL
RUN pecl install redis && docker-php-ext-enable redis

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código del proyecto
COPY . .

# Instalar dependencias de PHP (sin las de desarrollo, para producción)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Eliminar symlink viejo (copiado desde el host) y regenerarlo correctamente
RUN rm -f public/storage && php artisan storage:link

# Permisos correctos para Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Copiar configuración de Nginx y PHP-FPM
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/www.conf /usr/local/etc/php-fpm.d/www.conf

# Copiar configuración de Supervisor (arranca Nginx + PHP-FPM juntos)
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
