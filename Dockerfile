# Usa una imagen base con PHP y Apache preconfigurado
FROM php:8.2-apache

# Instala extensiones de PHP necesarias para conectarte a bases de datos
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copia los archivos de tu proyecto al contenedor
COPY appdocker/ /var/www/html/

# Configura permisos y expone el puerto 80
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80