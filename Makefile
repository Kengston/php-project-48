install:
	composer install

validate:
	composer validate

update:
	composer update

dump-autoload:
	composer dump-autoload

require:
	composer require --dev squizlabs/php_codesniffer

console:
	composer exec --verbose psysh

lint:
	./vendor/bin/phpcs
	composer exec --verbose phpstan

lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 src tests

test:
	composer exec --verbose phpunit tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
