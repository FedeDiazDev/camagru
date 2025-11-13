FROM php:8.3-fpm


RUN apt-get -y update && apt-get -y upgrade && \
    apt-get -y install git curl unzip vim && \
    rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

RUN mkdir -p /var/www/html/uploads && \
    mkdir -p /var/www/html/public/images/posts && \
    chown -R www-data:www-data /var/www/html/uploads /var/www/html/public/images && \
    chmod -R 775 /var/www/html/uploads /var/www/html/public/images

RUN composer require sendgrid/sendgrid

EXPOSE 9000

CMD ["php-fpm"]
