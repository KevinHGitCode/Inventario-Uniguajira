# Usa una imagen base de PHP con Apache
FROM php:8.1-apache

# Instala las extensiones necesarias para mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Instala dependencias necesarias para composer y extensiones PHP comunes
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    && docker-php-ext-install \
    zip \
    intl \
    mbstring \
    pdo_mysql

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia los archivos del proyecto al contenedor
COPY . /var/www/html/

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Instala las dependencias de Composer si existe un composer.json
RUN if [ -f "composer.json" ]; then \
        composer install --no-dev --optimize-autoloader; \
    fi

# Establece los permisos correctos para los archivos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Habilita el módulo de reescritura de Apache (opcional, si usas URLs amigables)
RUN a2enmod rewrite

# Configura Apache para usar mod_rewrite
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Expone el puerto 80 para el servidor web
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]