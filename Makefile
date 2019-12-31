build:
	@composer install && npm install

update upgrade:
	@composer update && npm update

clear-cache:
	@rm app/cache/* -rfv

test:
	@php-cs-fixer fix --diff --dry-run && psalm --show-info=false && phpunit
