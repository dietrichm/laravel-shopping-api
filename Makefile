.PHONY: build up logs down composer

user := $(shell id -u):$(shell id -g)

default: up

build: .env
	docker-compose build

up: .env vendor
	docker-compose up -d

logs:
	docker-compose logs -f

down:
	docker-compose down

.env:
	cp .env.example .env

vendor: composer.json composer.lock
	docker-compose run --rm --user $(user) php composer install

composer:
	docker-compose run --rm --user $(user) php composer $(filter-out $@,$(MAKECMDGOALS))

%:
	@:
