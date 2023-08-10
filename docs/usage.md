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
/**
 * Get a model collection
 * @return Collection
 */
public function index(): Collection
{
    return $this->userRepository->get(
        [
            'role' => 'user',                   // users.role = 'user'
            'name LIKE' => "%A%",               // && users.name LIKE "%A%"
            'id NOT IN' => [1,2,3],             // && users.id NOT IN (1,2,3)
            'created_at >=' => date('Y-m-d'),   // && users.created >= now()
        ],
        ['id', 'name'],                         // select columns names
        ['name' => 'ASC', 'id' => 'DESC']       // order by
    );
}

/**
 * Get a single line from the database
 * @return \Illuminate\Database\Eloquent\Model
 */
public function show(int $id): Model
{
    // $this->userRepository->find($id);
    // $this->userRepository->first(['id' => $id]);
    // $this->userRepository->firstOrFail(['id' => $id]);
    return $this->userRepository->findOrFail($id, ['id', 'name']);
}

/**
 * Store a new database row
 * @return \Illuminate\Database\Eloquent\Model
 */
public function store($request): Model
{
    return $this->userRepository->create([
        'name' => $request->name,
    ]);
}

/**
 * Update a database row by primary key
 * @return \Illuminate\Database\Eloquent\Model
 */
public function update(int $id, $request): Model
{
    // $user = $this->userRepository->findOrFail($id, ['*']);
    // return $this->userRepository->updateCollection($user, ['name' => ...]);

    return $this->userRepository->update($id, [
        'name' => $request->name,
    ]);
}

/**
 * Delete a database row
 * @return boolean
 */
public function delete(int $id): bool
{
    // $this->userRepository->forceDelete($id);
    return $this->userRepository->delete($id);
}

/**
 * Restores data by clearing the soft delete field
 * @return boolean
 */
public function restore(int $id): bool
{
    return $this->userRepository->restore($id); // Necessary SoftDelete
}
```

## Trashed Methods

Capture deleted or not deleted data:
Models with soft delete can use the `withTrashed` and `withoutTrashed` methods.

```php
/**
 * Return all users even with the soft deleted field populated
 * @return \Illuminate\Database\Eloquent\Model
 */
public function index(int $id): Model
{
    return $this->userRepository->withTrash()->get();
}

/**
 * Returns the user if soft deleted column is null
 * @return \Illuminate\Database\Eloquent\Model
 */
public function show(int $id): Model
{
    return $this->userRepository->withouTrash()->findOrFail($id);
}
```