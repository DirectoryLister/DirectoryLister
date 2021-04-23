ARTIFACT_FILES=$$(paste --delimiters ' ' --serial artifacts.include)
ARTIFACT_NAME="DirectoryLister-$$(git describe --tags --exact-match HEAD 2> /dev/null || git rev-parse --short HEAD)"
RUN := docker-compose run --rm app

dev development: # Build application for development
	@$(RUN) composer install --no-interaction
	@$(RUN) npm install && npm run dev

prod production: # Build application for production
	@$(RUN) composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
	@$(RUN) npm install --no-save && npm run production && npm prune --production

update upgrade: # Update application dependencies
	@$(RUN) composer update && npm update && npm install && npm audit fix

analyze: # Run coding standards and static analysis checks
	@$(RUN) app/vendor/bin/php-cs-fixer fix --diff --dry-run && app/vendor/bin/psalm
	@$(RUN) npx eslint app/resources/js/**/*.{js,vue}

test: analyze # Run coding standards/static analysis checks and tests
	@$(RUN) app/vendor/bin/phpunit --coverage-text

coverage: # Generate an HTML coverage report
	@docker-compose run --rm -e XDEBUG_MODE=coverage app app/vendor/bin/phpunit --coverage-html .coverage

tunnel: # Expose the application via secure tunnel
	@ngrok http -host-header=rewrite http://directory-lister.local:80

clear-assets: # Clear the compiled assets
	@$(RUN) rm app/assets/* -rfv

clear-cache: # Clear the application cache
	@$(RUN) rm app/cache/* -rfv

tar: # Generate tarball
	@$(RUN) tar --exclude-vcs --exclude=app/cache/* --exclude=app/resources \
		--create --gzip --file artifacts/$(ARTIFACT_NAME).tar.gz $(ARTIFACT_FILES)

zip: # Generate zip file
	@$(RUN) zip --quiet --exclude "*.git*" "app/cache/**" "app/resources/*" \
		--recurse-paths artifacts/$(ARTIFACT_NAME).zip $(ARTIFACT_FILES)

artifacts: clear-assets production tar zip # Generate release artifacts
