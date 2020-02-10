# Laravel Shopping API

This project is a POC (or MVP) for the back-end of an online shopping platform. Based on Laravel 6, it implements a small API offering REST endpoints to retrieve product data and create, read, update and checkout orders.

In order to use this project in a live environment, some further enhancements would need to happen, including implementing sessions. See _Future work_ for more details.

## Technical approach

The shopping back-end is built using PHP 7 on Laravel 6 and uses Domain-Driven Design as much as possible. Using DDD, the domain layer (containing all the business logic, commands and events) is kept separated as much as possible from the underlying application and infrastructural layers.

### Domain-Driven Design (DDD)

The domain layer resides in `domain/` and contains domain objects for _Products_ and _Orders_. It makes use extensively of value objects to represent properties of entities, implements commands and handlers for business scenarios, adds events that can take care of side effects and provides specific domain exceptions. The `Order` entity is an aggregate containing a collection of `LineItem` instances, each holding a reference to the `Product` being added.

While the project strives to have a clean separation between domain and application logic, it still uses Laravel and its base functionality, including Eloquent models, so a pure split-up does not take place. For instance, the `Product` entity is an Eloquent model containing scalar properties as opposed to value objects, and exposes the query builder methods it uses in the domain and application layer. The `Order` entity, on the other hand, is an aggregate root and firmly tied to the implementation offered by the event sourcing package. So, at this point in time, no separate repositories were implemented.

## Installation

1. Start the Docker containers by issuing `make`.  
   This will also build the containers, create an `.env` file and install vendor packages.
1. Run `make artisan key:generate` to generate and add the app key to `.env`.
1. Restart the containers using `make restart`.  
   This will populate the changes in the environment variables.
1. Create and seed the database tables using `make artisan -- migrate:fresh --seed`.  
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

Because the API also keeps track of which line items were removed, the list of removed line items with product data is included separately in the response.

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

### `POST /api/orders/[order-uuid]/checkout`

Finally, an order can be checked out by sending an email address to the specified endpoint:

```json
{
    "emailAddress": "me@example.org"
}
```

As of now, the order is immutable and hence no line items can be added or removed.
