.DEFAULT_GOAL:=help

REGEX = '(?<=\DB_VOLUME_NAME=)[a-zA-Z0-9\._-]*'
VOLUME := $(shell cat docker/.env | grep -oP ${REGEX})

.PHONY: build
build:
	cd docker && docker-compose build

.PHONY: up
up:
	cd docker && docker-compose up -d

.PHONY: down
down:
	cd docker && docker-compose down

.PHONY: rm-db
rm-db:
	cd docker && docker volume rm ${VOLUME}

.PHONY: logs
logs:
	cd docker && docker-compose logs -f

.PHONY: bash
bash:
	cd docker && docker-compose exec php-fpm bash

.PHONY: install
install:
	cd docker && docker-compose run --rm php-fpm sh -c 'composer install --no-interaction --ansi'

.PHONY: create-event-stream
create-event-stream:
	cd docker && docker-compose run --rm php-fpm sh -c "bin/console event-store:event-stream:create"

.PHONY: load-default-items-and-coins
load-default-items-and-coins:
	cd docker && docker-compose run --rm php-fpm sh -c "bin/console vending-machine:load"

.PHONY: run-coin-projection
run-coin-projection:
	cd docker && docker-compose run --rm php-fpm sh -c "bin/console event-store:projection:run coin_projection -o"

.PHONY: run-item-projection
run-item-projection:
	cd docker && docker-compose run --rm php-fpm sh -c "bin/console event-store:projection:run item_projection -o"

.PHONY: dump-autoload
dump-autoload:
	cd docker && docker-compose run --rm php-fpm sh -c 'composer dump-autoload --no-dev --classmap-authoritative'

.PHONY: test
test:
	cd docker && docker-compose run --rm php-fpm sh -c 'composer test'

.PHONY: unit
unit:
	cd docker && docker-compose run --rm php-fpm sh -c 'composer test:unit'

.PHONY: behat
behat:
	cd docker && docker-compose run --rm php-fpm sh -c 'composer test:behat'

.PHONY: cs
cs:
	cd docker && docker-compose run --rm php-fpm sh -c 'vendor/bin/php-cs-fixer fix --no-interaction --diff --verbose'

.PHONY: stan
stan:
	cd docker && docker-compose run --rm php-fpm sh -c 'vendor/bin/phpstan analyse --memory-limit=-1'
