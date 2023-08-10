composer-install:
	composer install

composer-update:
	composer update

composer-tests:
	composer tests

composer-check:
	composer check

composer-phpcbf:
	composer phpcbf

mkdocs-serve:
	mkdocs serve

mkdocs-build:
	mkdocs build --clean

mkdocs-deploy:
	mkdocs gh-deploy --clean