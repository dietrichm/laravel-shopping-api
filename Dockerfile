FROM php:7.4
RUN apt-get update && apt-get install -y --no-install-recommends git unzip
COPY . /code
WORKDIR /code
EXPOSE 80
CMD php artisan serve --host=0.0.0.0 --port=80
