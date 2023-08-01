migrate:
	php artisan migrate

migrate-rollback:
	php artisan migrate:rollback

migrate-fresh:
	php artisan migrate:fresh --seed

composer-install:
	composer install

composer-update:
	composer update

composer-tests:
	composer tests

code-check:
	composer phpstan
	composer phpcs

phpcbf:
	composer phpcbf

repository-create:
	php artisan repository:create $(model)

mkdocs-serve:
	mkdocs serve

mkdocs-build:
	mkdocs build --clean

mkdocs-deploy:
	mkdocs gh-deploy --clean