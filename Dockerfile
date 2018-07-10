FROM php:7.2-cli
RUN apt-get update && apt-get install -y libicu-dev git unzip --no-install-recommends
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install intl
RUN printf "\n" | pecl install redis
RUN docker-php-ext-enable redis
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
VOLUME .:/srv/http/php-crawler
WORKDIR /srv/http/php-crawler
CMD bash -c "composer install && php artisan serve --host=0.0.0.0 --port=8000"
