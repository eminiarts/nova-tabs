.PHONY: artisan

EXEC="docker-compose exec -u app workspace"
PHPUNIT="vendor/bin/phpunit"

uid=1000
gid=1000

up:
	docker-compose up -d

start: up

down:
	docker-compose down

stop: down

dcbuild:
	docker-compose build

phpcsfixer:
	"$(EXEC)" composer phpcsfixer

## Composer
composer:
	"$(EXEC)" composer $(cmd)

composer-install:
	"$(EXEC)" composer install

composer-require:
	"$(EXEC)" composer require $(pkg)

test:
	"$(EXEC)" php artisan test

test-all: eslint phpcsfixer test

sh:
	"$(EXEC)" sh

npm:
	"$(EXEC)" npm $(cmd)

npm-dev:
	"$(EXEC)" npm run dev

npm-watch:
	"$(EXEC)" npm run watch

npm-prod:
	"$(EXEC)" npm run prod

npx:
	"$(EXEC)" npx $(cmd)

eslint:
	"$(EXEC)" npm run eslint
