# Laravel Shopping API

## Running the application

1. Start the Docker containers by issuing `make`.  
   This will also build the containers, create an `.env` file and install vendor packages.
1. Run `make artisan key:generate` to generate and add the app key to `.env`.
1. Restart the containers using `make restart`.  
   This will populate the changes in the environment variables.
1. Create and seed the database tables using `make artisan -- migrate:fresh --seed`  
   This will import four demo products which have been provided in a JSON file.
1. Add `0.0.0.0 shopping.test` to your `/etc/hosts` file.

## Running tests

The test suite can be run with `make tests`.

## Working with the API

There are several API endpoints available, which can easily be accessed using an API client such as [Postman](https://www.getpostman.com/) or [Insomnia](https://insomnia.rest/).

### `GET /api/products`

This will return a list of products, including the ID, name and price.

### `POST /api/orders`

To create a new order, call this endpoint to retrieve a new order ID.
