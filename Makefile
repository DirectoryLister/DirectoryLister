ARTIFACT_FILES=$$(paste --delimiters ' ' --serial artifacts.include)
ARTIFACT_NAME="DirectoryLister-$$(git describe --tags --exact-match HEAD 2> /dev/null || git rev-parse --short HEAD)"

dev development: # Build application for development
	@composer install --no-interaction
	@npm install && npm run dev

prod production: # Build application for production
	@composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
	@npm install --no-save && npm run production && npm prune --production

update upgrade: # Update application dependencies
	@composer update && npm update && npm install && npm audit fix

php-cs-fixer: # Check PHP coding standards with PHP Coding Standards Fixer
	@app/vendor/bin/php-cs-fixer fix --diff --dry-run

eslint: # Check JavaScript coding standards with ESLint
	@npm run cs

cs coding-standards: php-cs-fixer eslint # Check coding standards

static-analysis: # Run static analysis checks
	@app/vendor/bin/phpstan

analyze: coding-standards static-analysis # Run coding standards and static analysis checks

test: # Run tests
	@app/vendor/bin/phpunit

suite: analyze test # Run coding standards and static analysis checks and tests

coverage: # Generate an HTML coverage report
	@docker-compose run --rm -e XDEBUG_MODE=coverage app app/vendor/bin/phpunit --coverage-html .coverage

tunnel: # Expose the application via secure tunnel
	@composer exec expose share directory-lister.local

clear-assets: # Clear the compiled assets
	@rm app/assets/* -rfv

clear-cache: # Clear the application cache
	@rm app/cache/* -rfv

tar: # Generate tarball
	@tar --exclude-vcs --exclude=app/cache/* --exclude=app/resources \
		--create --gzip --file artifacts/$(ARTIFACT_NAME).tar.gz $(ARTIFACT_FILES)

zip: # Generate zip file
	@zip --quiet --exclude "*.git*" "app/cache/**" "app/resources/*" \
		--recurse-paths artifacts/$(ARTIFACT_NAME).zip $(ARTIFACT_FILES)

artifacts: clear-assets production tar zip # Generate release artifacts
