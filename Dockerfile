FROM php:8.3-fpm

# Instalar dependencias del sistema para PHP y extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar directorio de trabajo
WORKDIR /var/www

# Copiar la aplicación al contenedor
COPY . /var/www

# Asignar permisos a la carpeta de la aplicación
RUN chown -R www-data:www-data /var/www

# Exponer el puerto 9000
EXPOSE 9000

# Comando para ejecutar PHP-FPM
CMD ["php-fpm"]
