ARTIFACT_FILES=app node_modules vendor .env.example directory-lister.svg LICENSE mix-manifest.json README.md index.php
ARTIFACT_NAME="DirectoryLister-$$(git describe --tags --exact-match HEAD 2> /dev/null || git rev-parse --short HEAD)"

dev development: # Build application for development
	@composer install --no-interaction
	@npm install && npm run dev

prod production: # Build application for production
	@composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
	@npm install --no-save && npm run production && npm prune --production

update upgrade: # Update application dependencies
	@composer update && npm update && npm install

test: #: Run coding standards/static analysis checks and tests
	@php-cs-fixer fix --diff --dry-run && psalm --show-info=false && phpunit --coverage-text

tunnel: # Expose the application via secure tunnel
	@ngrok http -host-header=rewrite http://directory-lister.local:80

clear-cache: # Clear the application cache
	@rm app/cache/* -rfv

tar: # Generate tarball
	@tar --verbose --create --gzip --exclude-vcs --exclude-vcs-ignores \
		--exclude app/cache/* --file artifacts/$(ARTIFACT_NAME).tar.gz \
		$(ARTIFACT_FILES)

zip: # Generate zip file
	@zip --exclude "app/cache/**" --exclude "*.git*" \
		--recurse-paths artifacts/$(ARTIFACT_NAME).zip $(ARTIFACT_FILES)

artifacts: clear-cache production tar zip # Generate release artifacts
