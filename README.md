# Laravel Shopping API

## Running the application

1. Start the Docker containers by issuing `make`.  
   This will also build the containers, create an `.env` file and install vendor packages.
1. Run `make artisan key:generate` to generate and add the app key to `.env`.
1. Restart the containers using `make restart`.  
   This will populate the changes in the environment variables.
1. Add `0.0.0.0 shopping.test` to your `/etc/hosts` file.
1. Access the application at [shopping.test](http://shopping.test).
