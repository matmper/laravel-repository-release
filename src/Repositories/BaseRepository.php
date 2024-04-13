<?php

namespace Matmper\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Matmper\Exceptions\EmptyArrayDataException;

class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Primary key column name
     *
     * @var string
     */
    protected $modelPrimaryKey;

    /**
     * Check if model has soft delete trait
     *
     * @var bool
     */
    private $isSoftDelete = false;

    /**
     * Use Eloquent withTrashed method
     *
     * @var boolean
     */
    private $withTrashed = false;

    /**
     * Use Eloquent withoutTrashed method
     *
     * @var boolean
     */
    private $withoutTrashed = false;

    public function __construct()
    {
        $this->load();
    }

    /**
     * Set to use withTrashed() Eloquent method
     *
     * @return self
     */
    public function withTrashed(): self
    {
        $this->withTrashed = $this->isSoftDelete;
        return $this;
    }

    /**
     * Set to use withoutTrashed() Eloquent method
     *
     * @return self
     */
    public function withoutTrashed(): self
    {
        $this->withoutTrashed = $this->isSoftDelete;
        return $this;
    }

    /**
     * Init a new model query builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): Builder
    {
        return $this->model->query();
    }

    /**
     * Eloquent model: find
     *
     * @param integer $id
     * @param array<string> $columns
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id, array $columns = []): ?Model
    {
        $query = $this->getBaseQuery([], $columns, []);

        return $query->find($id);
    }

    /**
     *  Eloquent model: findOrFail
     *
     * @param integer $id
     * @param array<string> $columns
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
     */
    public function findOrFail(int $id, array $columns = []): Model
    {
        $query = $this->getBaseQuery([], $columns, []);

        return $query->findOrFail($id);
    }

    /**
     * Eloquent model: first
     *
     * @param array<string, mixed> $where
     * @param array<string> $columns
     * @param array<string, string> $orderBy
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function first(array $where, array $columns = [], array $orderBy = []): ?Model
    {
        $query = $this->getBaseQuery($where, $columns, $orderBy);

        return $query->first();
    }

    /**
     * Eloquent model: firstOrFail
     *
     * @param array<string, mixed> $where
     * @param array<string> $columns
     * @param array<string, string> $orderBy
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
     */
    public function firstOrFail(array $where, array $columns = [], array $orderBy = []): Model
    {
        $query = $this->getBaseQuery($where, $columns, $orderBy);

        return $query->firstOrFail();
    }

    /**
     * Eloquent model: get
     *
     * @param array<string, mixed> $where
     * @param array<string> $columns
     * @param array<string, string> $orderBy
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(
        array $where,
        array $columns = [],
        array $orderBy = [],
    ): Collection {
        $query = $this->getBaseQuery($where, $columns, $orderBy);

        return $query->get();
    }

    /**
     * Eloquent model: get to base
     *
     * @param array<string, mixed> $where
     * @param array<string> $columns
     * @param array<string, string> $orderBy
     * @return \Illuminate\Support\Collection
     */
    public function getToBase(
        array $where,
        array $columns = [],
        array $orderBy = [],
    ): \Illuminate\Support\Collection {
        $query = $this->getBaseQuery($where, $columns, $orderBy);

        return $query->toBase()->get();
    }

    /**
     * Eloquent model: paginate
     *
     * @param array<string, mixed> $where
     * @param array<string> $columns
     * @param array<string, string> $orderBy
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(
        array $where,
        array $columns = [],
        array $orderBy = [],
        int $limit = 0,
    ): LengthAwarePaginator {
        $limit = $limit > 0 ? $limit : config('repository.default.paginate', 25);
        $query = $this->getBaseQuery($where, $columns, $orderBy);

        return $query->paginate($limit);
    }

    /**
     * Eloquent model: count
     *
     * @param array<string, mixed> $where
     * @return integer
     */
    public function count(array $where): int
    {
        $query = $this->getBaseQuery($where, [], []);

        return $query->count();
    }

    /**
     * Eloquent model: create (single row)
     *
     * @param array<string, mixed> $data
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Matmper\Exceptions\EmptyArrayDataException
     */
    public function create(array $data): Model
    {
        if (empty($data)) {
            throw new EmptyArrayDataException('$data');
        }

        return $this->query()->create($data);
    }

    /**
     * Eloquent model: insert (multiple rows)
     *
     * @param array<string, mixed> $data
     * @return boolean
     * @throws \Matmper\Exceptions\EmptyArrayDataException
     */
    public function insert(array $data): bool
    {
        if (empty($data)) {
            throw new EmptyArrayDataException('$data');
        }

        return $this->query()->insert($data);
    }

    /**
     * Eloquent model: firstOrCreate
     *
     * @param array<string, mixed> $where
     * @param array<string, mixed> $fields
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Matmper\Exceptions\EmptyArrayDataException
     */
    public function firstOrCreate(array $where, array $fields = []): Model
    {
        if (empty($where)) {
            throw new EmptyArrayDataException('$where');
        }

        return $this->query()->firstOrCreate($where, $fields);
    }

    /**
     * Eloquent model: updateOrCreate
     *
     * @param array<string, mixed> $where
     * @param array<string, mixed> $fields
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Matmper\Exceptions\EmptyArrayDataException
     */
    public function updateOrCreate(array $where, array $fields = []): Model
    {
        if (empty($where)) {
            throw new EmptyArrayDataException('$where');
        }

        return $this->query()->updateOrCreate($where, $fields);
    }

    /**
     * Eloquent model: update (row by primary key)
     *
     * @param integer $itemPrimaryKey
     * @param array<string, mixed> $data
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Matmper\Exceptions\EmptyArrayDataException
     */
    public function update(int $itemPrimaryKey, array $data): Model
    {
        if (empty($data)) {
            throw new EmptyArrayDataException('$data');
        }

        $item = $this->findOrFail($itemPrimaryKey, ['*']);

        return $this->updateCollection($item, $data);
    }

    /**
     * Eloquent model: update save (collection)
     *
     * @param \Illuminate\Database\Eloquent\Model $item
     * @param array<string, mixed> $data
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Matmper\Exceptions\EmptyArrayDataException
     */
    public function updateCollection(Model $item, array $data): Model
    {
        if (empty($data)) {
            throw new EmptyArrayDataException('$data');
        }

        foreach ($data as $key => $value) {
            $item->{$key} = $value;
        }

        $item->save();

        return $item;
    }

    /**
     * Eloquent model: restore
     *
     * @param integer $itemPrimaryKey
     * @return boolean
     * @throws \Matmper\Exceptions\OnlyModelsWithSoftDeleteException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
     */
    public function restore(int $itemPrimaryKey): bool
    {
        if (!$this->isSoftDelete) {
            throw new \Matmper\Exceptions\OnlyModelsWithSoftDeleteException('restore');
        }

        return $this->withTrashed()->findOrFail($itemPrimaryKey, [])->restore(); /** @phpstan-ignore-line */
    }

    /**
     * Eloquent model: delete
     *
     * @param integer $itemPrimaryKey
     * @return boolean
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
     */
    public function delete(int $itemPrimaryKey): bool
    {
        return $this->findOrFail($itemPrimaryKey, [])->delete();
    }

    /**
     * Eloquent model: force delete
     *
     * @param integer $itemPrimaryKey
     * @return boolean
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
     */
    public function forceDelete(int $itemPrimaryKey): bool
    {
        return $this->findOrFail($itemPrimaryKey, [])->forceDelete();
    }

    /**
     * Create base query builder
     *
     * @param array<string, mixed> $where
     * @param array<string> $columns
     * @param array<string, string> $orderBy
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getBaseQuery(array $where, array $columns, array $orderBy): Builder
    {
        $query = $this->query();

        $this->scopeMakeSelect($query, $columns);
        $this->scopeMakeWhere($query, $where);
        $this->scopeMakeOrderBy($query, $orderBy);
        $this->validateAndSetBuildWithTrashed($query);

        return $query;
    }

    /**
     * Create select columns query builder
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array<string> $columns
     * @return void
     */
    private function scopeMakeSelect(Builder &$query, array $columns): void
    {
        $columns = !empty($columns) ? $columns : $this->model->getKeyName();
        $query->select($columns);
    }

    /**
     * Create where conditionals query builder
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array<string, mixed> $where
     * @return void
     * @throws \Matmper\Exceptions\EmptyArrayDataException
     */
    private function scopeMakeWhere(Builder &$query, array $where): void
    {
        foreach ($where as $key => $value) {
            $keyExplode = explode(' ', trim($key));
            $column = array_shift($keyExplode);
            $operator = !empty($keyExplode) ? strtoupper(implode(' ', $keyExplode)) : '=';

            // WHERE IN ['column' => [1,2]]
            if (in_array($operator, ['IN', '=']) && is_array($value)) {
                $query->whereIn($column, $value);
            // WHERE NOT IN ['column !=' => [1,2]]
            } elseif (in_array($operator, ['NOT IN', '!=', '<>']) && is_array($value)) {
                $query->whereNotIn($column, $value);
            // WHERE IS NULL ['column' => null]
            } elseif (in_array($operator, ['IS NULL', '=']) && is_null($value)) {
                $query->whereNull($column);
            // WHERE IS NOT NULL ['column !=' => null]
            } elseif (in_array($operator, ['IS NOT NULL', '!=', '<>']) && is_null($value)) {
                $query->whereNotNull($column);
            // WHERE DEFAULT
            } else {
                $query->where($column, $operator, $value);
            }
        }
    }

    /**
     * Create order by query builder
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array<string, string> $orderBy
     * @return void
     */
    private function scopeMakeOrderBy(Builder &$query, array $orderBy): void
    {
        foreach ($orderBy as $key => $value) {
            $query->orderBy($key, $value);
        }
    }

    /**
     * Set with and without trashed query builder
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    private function validateAndSetBuildWithTrashed(Builder &$query)
    {
        if ($this->withoutTrashed) {
            $query->withoutTrashed(); /** @phpstan-ignore-line */
        } elseif ($this->withTrashed) {
            $query->withTrashed(); /** @phpstan-ignore-line */
        }
    }

    /**
     * Instance and init
     *
     * @return void
     */
    private function load(): void
    {
        if (! ($this->model instanceof Model)) {
            $this->model = app()->make($this->model);
        }

        $this->modelPrimaryKey = $this->model->getKeyName();
        $this->isSoftDelete = method_exists($this->model, 'initializeSoftDeletes');
        $this->withTrashed = $this->isSoftDelete && config('repository.default.with_trashed', false);
    }
}
