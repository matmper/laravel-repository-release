# Usage

Instantiate the package in the desired service and call the desired method.

Load class with constructor property:
```php
use \App\Repositories\UserRepository;

public function __construct(private UserRepository $userRepository)
{
    //
}
```

As above, the repository will be stored in `$this->userRepository`, but it is also possible to load and use individually:

```php
$userRepository = new \App\Repositories\UserRepository;
$user = $userRepository->findOrFail(1);
```

## Query methods

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