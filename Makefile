build: install compile # Install application dependencies and build assets

install: # Install application dependencies
	@composer install && npm install

compile: # Compile application (CSS and JavaScript) assets
	@npm run dev

update upgrade: # Update application dependencies
	@composer update && npm update && npm install

test: #: Run coding standards/static analysis checks and tests
	@php-cs-fixer fix --diff --dry-run && psalm --show-info=false && phpunit

tunnel: # Expose the application via secure tunnel
	@ngrok http -host-header=rewrite http://directory-lister.local:80

clear-cache: # Clear the application cache
	@rm app/cache/* -rfv
