FROM php:8.2.0-apache

COPY ./ /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite \
    && docker-php-ext-install pdo_mysql

ENV APACHE_PORT=8080

RUN sed -i "s/80/${APACHE_PORT}/g" /etc/apache2/sites-available/000-default.conf
RUN sed -i "s/80/${APACHE_PORT}/g" /etc/apache2/ports.conf

EXPOSE 8080