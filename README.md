# BR Lab Symfony Test v3.2

## Info

APP: `[http://localhost:8080](http://localhost:8080)`

Documentation: `[http://localhost:8080/docs](http://localhost:8080/docs)`

## Commands

Environment: 
- Init project: `make init`
- Docker up: `make up`
- Docker down: `make down`
- Docker down + up: `make restart`

App:
- Composer install: `make composer-install`
- Composer update: `make composer-update`
- Generate docs: `make docs`
- Run migrations: `make migrations`
- Run fixtures: `make fixtures`

Tests:
- All tests: `make test`
- Unit tests: `make test-unit`
- All tests + coverage: `make test-coverage`
- Unit tests + coverage: `make test-unit-coverage`

> Path to code coverage: `/app/var/coverage`