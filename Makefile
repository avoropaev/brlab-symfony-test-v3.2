up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear app-clear docker-pull docker-build docker-up app-init
test: app-test
test-coverage: app-test-coverage
test-unit: app-test-unit
test-unit-coverage: app-test-unit-coverage

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

app-init: app-composer-install app-assets-install app-wait-db app-migrations app-fixtures app-ready

app-ready:
	docker run --rm -v ${PWD}/app:/app --workdir=/app alpine touch .ready

app-clear:
	docker run --rm -v ${PWD}/app:/app --workdir=/app alpine rm -f .ready

app-composer-install:
	docker-compose run --rm app-php-cli composer install

app-assets-install:
	docker-compose run --rm app-node yarn install
	docker-compose run --rm app-node npm rebuild node-sass

app-migrations:
	docker-compose run --rm app-php-cli php bin/console doctrine:migrations:migrate --no-interaction

app-fixtures:
	docker-compose run --rm app-php-cli php bin/console doctrine:fixtures:load --no-interaction

app-test:
	docker-compose run --rm app-php-cli php bin/phpunit

app-test-coverage:
	docker-compose run --rm app-php-cli php bin/phpunit --coverage-clover var/clover.xml --coverage-html var/coverage

app-test-unit:
	docker-compose run --rm app-php-cli php bin/phpunit --testsuite=unit

app-test-unit-coverage:
	docker-compose run --rm app-php-cli php bin/phpunit --testsuite=unit --coverage-clover var/clover.xml --coverage-html var/coverage

app-wait-db:
	until docker-compose exec -T app-postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done