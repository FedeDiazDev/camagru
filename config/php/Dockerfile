FROM php:7.4-fpm

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Definir directorio de trabajo
WORKDIR /var/www/html

COPY . /var/www/html/

RUN mkdir -p /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/public/uploads \
    && chmod -R 755 /var/www/html/public/uploads

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

CMD ["docker-entrypoint.sh"]
