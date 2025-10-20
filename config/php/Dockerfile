FROM php:8.3-fpm


RUN apt-get -y update && apt-get -y upgrade && \
    apt-get -y install git curl unzip vim && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 775 /var/www/html/uploads

RUN composer require sendgrid/sendgrid

EXPOSE 9000

CMD ["php-fpm"]

# COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
# RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# CMD ["docker-entrypoint.sh"]
