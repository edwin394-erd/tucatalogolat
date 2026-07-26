FROM php:8.2-fpm-alpine
# Instala extensiones y copia tu app
WORKDIR /var/www
COPY . .
RUN apt-get update && apt-get install -y unzip libpng-dev libonig-dev libxml2-dev
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd
CMD php artisan serve --host=0.0.0.0 --port=$PORT