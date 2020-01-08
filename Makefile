build: # Install application dependencies
	@composer install && npm install

update upgrade: # Update application dependencies
	@composer update && npm update

test: #: Run coding standards/static analysis checks and tests
	@php-cs-fixer fix --diff --dry-run && psalm --show-info=false && phpunit

clear-cache: # Clear the application cache
	@rm app/cache/* -rfv

tunnel: # Expose the application via ngrok
	@ngrok http -host-header=rewrite http://directorylister.local:80

help: # Show this help
	@grep --perl-regexp '^([\w\s-]+):\s+#+\s+(.*)$$' $(MAKEFILE_LIST) \
		| awk 'BEGIN {FS = ": # "}; { printf "\033[36m%-16s\033[0m %s\n", $$1, $$2 }'
