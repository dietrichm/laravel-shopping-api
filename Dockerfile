FROM php:7.4
RUN apt-get update && apt-get install -y --no-install-recommends git unzip
RUN docker-php-ext-install pdo_mysql
COPY . /code
COPY --from=composer:1 /usr/bin/composer /usr/bin/composer
WORKDIR /code
EXPOSE 80
CMD php artisan serve --host=0.0.0.0 --port=80
