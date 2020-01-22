FROM php:7.4
COPY . /code
WORKDIR /code
EXPOSE 80
CMD php artisan serve --host=0.0.0.0 --port=80
