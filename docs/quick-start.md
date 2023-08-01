Create new repository file:

```bash
# create single model repository file from User model
$ php artisan repository:create User

# create all pending model repositories files
$ php artisan repository:create all
```

Files will create into `app/Repositories` path. To use instance file:

```php
use \App\Repositories\UserRepository;

public function __construct(private UserRepository $userRepository)
{
    //
}
```

Now package is disponible to use:

```php
public function index(): Collection
{
    return $this->userRepository->get(
        ['id >' => 0], // where
        ['id', 'name'], // columns
        ['id' => 'DESC'] // order by
    );
}

public function show(int $id): Model
{
    return $this->userRepository->findOrFail($id, ['id', 'name']);
}

public function store($request): Model
{
    return $this->userRepository->create([
        'name' => $request->name,
    ]);
}

public function update(int $id, $request): Model
{
    // $user = $this->userRepository->findOrFail($id, ['*']);
    // return $this->userRepository->updateCollection($user, ['name' => ...]);

    return $this->userRepository->update($id, [
        'name' => $request->name,
    ]);
}

public function delete(int $id): bool
{
    // return $this->userRepository->forceDelete($id);
    return $this->userRepository->delete($id);
}

public function restore(int $id): bool
{
    return $this->userRepository->restore($id); // Necessary SoftDelete
}
```