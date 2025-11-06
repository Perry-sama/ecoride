# back/Dockerfile
FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git unzip zip libicu-dev libonig-dev libxml2-dev libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql intl opcache zip

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN if [ -f composer.json ]; then composer install --no-dev --prefer-dist --no-interaction --no-progress --no-suggest || true; fi

COPY . .

RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor || true

EXPOSE 9000
CMD ["php-fpm"]
