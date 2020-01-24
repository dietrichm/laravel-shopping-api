.PHONY: build up logs down

default: up

build: .env
	docker-compose build

up: .env
	docker-compose up -d

logs:
	docker-compose logs -f

down:
	docker-compose down

.env:
	cp .env.example .env
