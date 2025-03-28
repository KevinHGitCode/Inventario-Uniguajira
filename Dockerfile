# Usa una imagen base de PHP con Apache
FROM php:8.1-apache

# Instala las extensiones necesarias para mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copia los archivos del proyecto al contenedor
COPY . /var/www/html/

# Establece los permisos correctos para los archivos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Habilita el módulo de reescritura de Apache (opcional, si usas URLs amigables)
RUN a2enmod rewrite

# Expone el puerto 80 para el servidor web
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]