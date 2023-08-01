# Laravel Repository Release - v1.x

Github: [matmper/laravel-repository-release](https://github.com/matmper/laravel-repository-release)

# Install

Install this repository in your vendor path:

```bash
$ composer require matmper/laravel-repository-release
```

Publish it to instance and create `/config/repository.php`:
```bash
$ php artisan vendor:publish --provider="Matmper\Providers\RepositoryProvider"
```