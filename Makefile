up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear clear docker-pull docker-build docker-up composer-install wait-db migrations fixtures ready


test:
	docker-compose run --rm php-cli php bin/phpunit
test-coverage:
	docker-compose run --rm php-cli php bin/phpunit --coverage-clover var/clover.xml --coverage-html var/coverage
test-unit:
	docker-compose run --rm php-cli php bin/phpunit --testsuite=unit
test-unit-coverage:
	docker-compose run --rm php-cli php bin/phpunit --testsuite=unit --coverage-clover var/clover.xml --coverage-html var/coverage


composer-install:
	docker-compose run --rm php-cli composer install
composer-update:
	docker-compose run --rm php-cli composer update
docs:
	docker-compose run --rm php-cli php bin/console docs:generate
migrations:
	docker-compose run --rm php-cli php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
fixtures:
	docker-compose run --rm php-cli php bin/console doctrine:fixtures:load --no-interaction


docker-down-clear:
	docker-compose down -v --remove-orphans
clear:
	docker run --rm -v ${PWD}/app:/app --workdir=/app alpine rm -f .ready
docker-pull:
	docker-compose pull
docker-build:
	docker-compose build
docker-up:
	docker-compose up -d
wait-db:
	until docker-compose exec -T postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done
ready:
	docker run --rm -v ${PWD}/app:/app --workdir=/app alpine touch .ready
docker-down:
	docker-compose down --remove-orphans