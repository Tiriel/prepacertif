SHELL := /bin/bash

install:
	symfony composer install
	yarn install
.PHONY: install

start:
	docker-compose up -d
	yarn encore dev
.PHONY: start

db-init:
	symfony console doctrine:database:drop --force --if-exists
	symfony console doctrine:database:create
	symfony console doctrine:migrations:migrate -n
.PHONY: db-init

fixtures:
	symfony console doctrine:fixtures:load -n
.PHONY: fixtures

db: db-init fixtures
.PHONY: db

tests:
	symfony console doctrine:database:drop --force --if-exists -e test
	symfony console doctrine:database:create -e test
	symfony console doctrine:migrations:migrate -n -e test
	symfony console doctrine:fixtures:load -n -e test
	symfony php bin/phpunit $@
.PHONY: tests

trans:
	symfony console translation:extract en --domain=messages --force
	symfony console translation:extract fr --domain=messages --force