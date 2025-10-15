FROM php:7.4-fpm


RUN apt-get -y update && apt-get -y upgrade && \
    apt-get -y install git curl unzip vim && \
    rm -rf /var/lib/apt/lists/*
    
RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html/

RUN mkdir -p /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/public/uploads \
    && chmod -R 755 /var/www/html/public/uploads

RUN chown -R www-data:www-data /var/www/html

RUN composer require sendgrid/sendgrid

EXPOSE 9000

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

CMD ["docker-entrypoint.sh"]
