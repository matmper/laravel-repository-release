# Quick Start

In the quick guide we will exemplify the use of a **User Model** repository!

## Create new repository

Run the command below to generate the `app/Repositories/UserRepository.php` file:
```bash
$ php artisan repository:create User
```

An alternative is to generate all pending repositories:
```
$ php artisan repository:create all
```

This is an example of the generated `UserRepository.php` file.

```php
namespace App\Repositories;

use Matmper\Repositories\BaseRepository;

final class UserRepository extends BaseRepository
{
    /**
     * @var \App\Models\User;
     */
    protected $model;

    public function __construct()
    {
        $this->model = new \App\Models\User();
        parent::__construct();
    }
}
```

# Simplified use

User Repository is ready to use.
Instantiate the package in the desired service and call the desired method.

```php
use \App\Repositories\UserRepository;

public function __construct(private UserRepository $userRepository)
{
    //
}

public function show(int $id): Model
{
    return $this->userRepository->findOrFail($id, ['id', 'name']);
}
```
