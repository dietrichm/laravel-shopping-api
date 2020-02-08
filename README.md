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

All of the following endpoints are designed to take and eventually return JSON data. Hence, use the following request headers for every call:

```
Content-Type: application/json
Accept: application/json
```

### `GET /api/products`

This will return a list of products, including the ID, name and price.

### `POST /api/orders`

To create a new order, call this endpoint to retrieve a new order ID.

### `GET /api/orders/[order-uuid]`

The info of any created order will be returned using this API endpoint. It shows the total value of the order along with all currently included line items with product info. Replace `[order-uuid]` with a valid order ID.

Because the API also keeps track of which line items were removed, the list of returned line items with product data is included separately in the response.

### `POST /api/orders/[order-uuid]/lineitems`

Once an order has been created, a line item can be added using this endpoint. Replace `[order-uuid]` in the path and use the following JSON payload:

```json
{
    "productId": "f9b81b04-c4ac-4da5-80c6-4bf44c68caf4"
}
```

Replace `productId` with a valid product ID retrieved from `/api/products`. The created line item ID will be returned.

### `DELETE /api/orders/[order-uuid]/lineitems`

An existing line item can be deleted again from an order by issuing the following request using `DELETE`:

```json
{
    "lineItemId": "b882af8e-102a-44b0-bd8f-4388a6d6721c"
}
```

Replace `lineItemId` with a valid line item ID.
