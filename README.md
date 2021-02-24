# Laravel Shopping API

![PHP](https://github.com/dietrichm/laravel-shopping-api/workflows/PHP/badge.svg) ![Laravel](https://github.com/dietrichm/laravel-shopping-api/workflows/Laravel/badge.svg)

This project is a POC for the back-end of an online shopping platform. Based on Laravel 6, it implements a small API offering REST endpoints to retrieve product data and create, read, update and checkout orders.

**Note:** lacking some essential auxiliary functionality such as sessions, this project is not suitable for use in a production environment.

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->

- [Technical approach](#technical-approach)
  - [Domain-Driven Design (DDD)](#domain-driven-design-ddd)
  - [Event sourcing](#event-sourcing)
- [Installation](#installation)
- [Running tests](#running-tests)
- [Working with the API](#working-with-the-api)
- [License](#license)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Technical approach

The shopping back-end is built using PHP 7 on Laravel 6 and uses Domain-Driven Design as much as possible. Using DDD, the domain layer (containing all the business logic, commands and events) is kept separated as much as possible from the underlying application and infrastructural layers.

### Domain-Driven Design (DDD)

The domain layer resides in `domain/` and contains domain objects for _Products_ and _Orders_. It makes use extensively of value objects to represent properties of entities, implements commands and handlers for business scenarios, adds events that can take care of side effects and provides specific domain exceptions. The `Order` entity is an aggregate containing a collection of `LineItem` instances, each holding a reference to the `Product` being added.

While the project strives to have a clean separation between domain and application logic, it still uses Laravel and its base functionality, including Eloquent models, so a pure split-up does not take place. For instance, the `Product` entity is an Eloquent model containing scalar properties as opposed to value objects, and exposes the query builder methods it uses in the domain and application layer. The `Order` entity, on the other hand, is an aggregate root and firmly tied to the implementation offered by the event sourcing package. So, at this point in time, no separate repositories were implemented.

### Event sourcing

We want to keep track of which items were added but also removed from the basket. Both adding as well as removing an item from an order are business commands in the domain layer, each having their proper event.

The shopping platform makes use of event sourcing and persists all Order domain events in the `Order` aggregate root. Recording a new event updates the current state of the aggregate while loading an `Order` from the database replays all domain events that have happened on it, meticulously recreating the state of the `Order` as it was when it was persisted. In particular, removed line items are being collected on the `Order` separately, so they can still be returned from the API in a distinct array in the order JSON data.

Using event sourcing, at every point in time the application knows _exactly_ how the current state was achieved, and can make future decisions based on past actions, such as applying discounts for removed line items.

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

Refer to the [API documentation](docs/api.apib) in an [API Blueprint](https://apiblueprint.org/) file.

## License

Copyright 2020, Dietrich Moerman.

Released under the terms of the [GNU Affero General Public License v3.0](LICENSE).
