# Usa la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala PDO PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Copia los archivos de tu proyecto al contenedor
COPY . /var/www/html/

# Exponer el puerto 80
EXPOSE 80
