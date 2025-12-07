FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y ca-certificates

COPY . /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]
