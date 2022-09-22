SHELL := /bin/bash

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