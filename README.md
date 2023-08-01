# Rest API - Users Transactions

Package to simplify the use of repositories in Laravel

<p align="center">
    <a href="http://makeapullrequest.com">
        <img src="https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square" alt="PRs Welcome">
    </a>
    <a href="https://github.com/matmper/laravel-repository-release/actions/workflows/github_actions.yml?query=branch%3Amain+event%3Apush">
        <img src="https://github.com/matmper/laravel-repository-release/actions/workflows/github_actions.yml/badge.svg?event=push" alt="License MIT">
    </a>
    <a href="https://en.wikipedia.org/wiki/Proprietary_software">
        <img src="https://img.shields.io/badge/license-Proprietary-blue.svg?style=flat-square" alt="License MIT">
    </a>
</p>

# Dependences
- PHP >= 8.0 ([Doc](https://www.php.net/releases/8.0/pt_BR.php))
- Laravel >= 9.x ([Doc](https://laravel.com/docs/9.x/releases))

# Install

Install this repository in your vendor path:

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

## Code

```php
public function show (int $id): User
{
    $userRepository = new \App\Repositories\UserRepository;
    return $userRepository->findOrFail($id);
}
```

This is will out

---

## Contribution & Development

This is an open source code, free for distribution and contribution. All contributions will be accepted only with Pull Request and that pass the test and code standardization.

Run composer install in yout development env:
```bash
$ composer install --dev --prefer-dist
```

Now you can use `composer check` in your terminal.