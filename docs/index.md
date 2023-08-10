# Laravel Repository Release - v1.x

Github: [matmper/laravel-repository-release](https://github.com/matmper/laravel-repository-release)

Use the package to add a layer between modeling and services.
# Install

Install this package with Composer:
```bash
$ composer require matmper/laravel-repository-release
```

Use the command below to publish the package and generate the `/config/repository.php` file:
```bash
$ php artisan vendor:publish --provider="Matmper\Providers\RepositoryProvider"
```