.PHONY: check
check: lint cs tests phpstan

.PHONY: tests
tests:
	php vendor/bin/phpunit

.PHONY: lint
lint:
	php vendor/bin/parallel-lint --colors \
		src tests \
		--exclude tests/Rule/Nette/data

.PHONY: cs-install
cs-install:
	git clone https://github.com/phpstan/build-cs.git || true
	git -C build-cs fetch origin && git -C build-cs reset --hard origin/main
	composer install --working-dir build-cs

.PHONY: cs
cs:
	php build-cs/vendor/bin/phpcs --standard=phpcs.xml src tests

.PHONY: cs-fix
cs-fix:
	php build-cs/vendor/bin/phpcbf --standard=phpcs.xml src tests

.PHONY: phpstan
phpstan:
	php vendor/bin/phpstan analyse -l 8 -c phpstan.neon src tests
