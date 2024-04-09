# Laravel Repository Release

Package to simplify the use of database repositories in Laravel

<a href="https://github.com/matmper/laravel-repository-release/pulls" target="_blank">
    <img src="https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=for-the-badge" alt="PRs Welcome">
</a>
<a href="https://packagist.org/packages/matmper/laravel-repository-release" target="_blank">
    <img src="https://img.shields.io/packagist/v/matmper/laravel-repository-release?style=for-the-badge&color=%23b6e673" alt="Packagist">
</a>
<a href="https://github.com/matmper/laravel-repository-release/actions/workflows/github_actions.yml?query=branch%3Amain" target="_blank">
    <img src="https://img.shields.io/github/actions/workflow/status/matmper/laravel-repository-release/github_actions.yml?branch=main&style=for-the-badge" alt="Github Actions">
</a>
<a href="https://opensource.org/license/mit/" target="_blank">
    <img src="https://img.shields.io/badge/license-MIT-blue.svg?style=for-the-badge" alt="License MIT">
</a>

# Dependences
- PHP >= 8.0.2 ([Doc](https://www.php.net/releases/8.0/pt_BR.php))
- Laravel >= 9.1.15 ([Doc](https://laravel.com/docs/9.x/releases))

# Install

Install this database srepository in your vendor path:

```bash
# install package
$ composer require matmper/laravel-repository-release

# publish package
$ php artisan vendor:publish --provider="Matmper\Providers\RepositoryProvider"
````

# Usage

Create new repository file
```bash
# create single model repository file
$ php artisan repository:create User

# create all pending model repositories files
$ php artisan repository:create all
```

Repository file example:
```php
// app/Repositories/UserRepository.php

namespace App\Repositories;

use Matmper\Repositories\BaseRepository;
use App\Models\User;

class UserRepository extends BaseRepository
{
    protected $model = User::class;
}
```

## Documentation

Access: [laravel-repository-release](https://matmper.github.io/laravel-repository-release/)

| laravel-repository-release version | Laravel versions |
|---|---|
| 1.x  | 9 / 10 / 11 |

## Contribution & Development

This is an open source code, free for distribution and contribution. All contributions will be accepted only with Pull Request and that pass the test and code standardization.

Run composer install in yout development env:
```bash
$ composer install --dev --prefer-dist
```

Now you can use `composer check` in your terminal.
