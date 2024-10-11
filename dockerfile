# Utilizamos la imagen oficial de PHP con Apache
FROM php:8.0-apache

# Instala las dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    libssl-dev && \
    rm -rf /var/lib/apt/lists/* && \  
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo_mysql mbstring zip exif pcntl

# Instalar la extensión de MongoDB
RUN pecl install mongodb && \
    docker-php-ext-enable mongodb

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Copiar archivos del proyecto a la carpeta del servidor web
COPY . /var/www/html/

# Copiamos el archivo de configuración custom.conf
COPY ./custom.conf /etc/apache2/conf-available/custom.conf

# Habilitamos la configuración de custom
RUN a2enconf custom

# Establecer permisos
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Ejecutar Apache en primer plano
CMD ["apache2-foreground"]
