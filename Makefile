.PHONY: test

init:
	rm -rf migrations/* && \
	php bin/console doctrine:database:create && \
	php bin/console make:migration && \
	php bin/console doctrine:migration:migrate

test:
	php bin/phpunit

test_init:
	php bin/console doctrine:database:create --env test && \
	php bin/console doctrine:migration:migrate --env test
