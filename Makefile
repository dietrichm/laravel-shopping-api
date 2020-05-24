.PHONY: build up logs down restart composer artisan lint lint-fix analyse tests

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

restart: down up

.env:
	cp .env.example .env

vendor: composer.json composer.lock
	docker-compose run --rm --user $(user) php composer install

composer:
	docker-compose run --rm --user $(user) php composer $(filter-out $@,$(MAKECMDGOALS))

artisan:
	docker-compose exec php php artisan $(filter-out $@,$(MAKECMDGOALS))

lint:
	docker-compose exec php vendor/bin/php-cs-fixer fix --verbose --dry-run --diff

lint-fix:
	docker-compose exec php vendor/bin/php-cs-fixer fix --verbose

analyse:
	docker-compose exec php vendor/bin/phpstan analyse

tests:
	docker-compose exec php vendor/bin/phpunit $(filter-out $@,$(MAKECMDGOALS))

%:
	@:
