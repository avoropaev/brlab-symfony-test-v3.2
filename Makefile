up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear clear docker-pull docker-build docker-up init
test: test
test-coverage: test-coverage
test-unit: test-unit
test-unit-coverage: test-unit-coverage

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

init: composer-install wait-db migrations fixtures ready

ready:
	docker run --rm -v ${PWD}/app:/app --workdir=/app alpine touch .ready

clear:
	docker run --rm -v ${PWD}/app:/app --workdir=/app alpine rm -f .ready

composer-install:
	docker-compose run --rm php-cli composer install

migrations:
	docker-compose run --rm php-cli php bin/console doctrine:migrations:migrate --no-interaction

fixtures:
	docker-compose run --rm php-cli php bin/console doctrine:fixtures:load --no-interaction

test:
	docker-compose run --rm php-cli php bin/phpunit

test-coverage:
	docker-compose run --rm php-cli php bin/phpunit --coverage-clover var/clover.xml --coverage-html var/coverage

test-unit:
	docker-compose run --rm php-cli php bin/phpunit --testsuite=unit

test-unit-coverage:
	docker-compose run --rm php-cli php bin/phpunit --testsuite=unit --coverage-clover var/clover.xml --coverage-html var/coverage

wait-db:
	until docker-compose exec -T postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done