<?php

namespace Matmper\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;
use Matmper\Exceptions\EmptyArrayDataException;

class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

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

    public function __construct()
    {
        $this->model = app($this->model);
        $this->isSoftDelete = method_exists($this->model, 'initializeSoftDeletes');
        $this->withTrashed = $this->isSoftDelete && Config::get('repository.default.with_trashed', false);
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
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id, array $columns = ['id']): ?Model
    {
        $query = $this->getBaseQuery([], $columns, []);
        
        return $query->find($id);
    }

    /**
     *  Eloquent model: findOrFail
     *
     * @param integer $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
     */
    public function findOrFail(int $id, array $columns = ['id']): Model
    {
        $query = $this->getBaseQuery([], $columns, []);
        
        return $query->findOrFail($id);
    }

    /**
     * Eloquent model: first
     *
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function first(array $where, array $columns = ['id'], array $orderBy = []): ?Model
    {
        $query = $this->getBaseQuery($where, $columns, $orderBy);
        
        return $query->first();
    }

    /**
     * Eloquent model: firstOrFail
     *
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
     */
    public function firstOrFail(array $where, array $columns = ['id'], array $orderBy = []): Model
    {
        $query = $this->getBaseQuery($where, $columns, $orderBy);
        
        return $query->firstOrFail();
    }

    /**
     * Eloquent model: get
     *
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(
        array $where,
        array $columns = ['id'],
        array $orderBy = [],
    ): Collection {
        $query = $this->getBaseQuery($where, $columns, $orderBy);

        return $query->get();
    }

    /**
     * Eloquent model: get to base
     *
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @return \Illuminate\Support\Collection
     */
    public function getToBase(
        array $where,
        array $columns = ['id'],
        array $orderBy = [],
    ): \Illuminate\Support\Collection {
        $query = $this->getBaseQuery($where, $columns, $orderBy);

        return $query->toBase()->get();
    }

    /**
     * Eloquent model: paginate
     *
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(
        array $where,
        array $columns = ['id'],
        array $orderBy = [],
        int $limit = 0,
    ): LengthAwarePaginator {
        $limit = $limit > 0 ? $limit : Config::get('repository.default.paginate', 25);
        $query = $this->getBaseQuery($where, $columns, $orderBy);

        return $query->paginate($limit);
    }

    /**
     * Eloquent model: count
     *
     * @param array $where
     * @return integer
     */
    public function count(array $where): int
    {
        $query = $this->getBaseQuery($where, ['id'], []);
        
        return $query->count();
    }

    /**
     * Eloquent model: create (single row)
     *
     * @param array $data
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
     * @param array $data
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
     * @param array $where
     * @param array $fields
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
     * @param array $where
     * @param array $fields
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
     * @param array $data
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
     * @param array $data
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
        
        /** @phpstan-ignore-next-line */
        return $this->query()->withTrashed()->findOrFail($itemPrimaryKey)->restore();
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
        return $this->query()->findOrFail($itemPrimaryKey, ['id'])->delete();
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
        $query = $this->query()->findOrFail($itemPrimaryKey, ['id']);

        if ($this->isSoftDelete) {
            /** @phpstan-ignore-next-line */
            $query->withTrashed();
        }

        return $query->forceDelete();
    }

    /**
     * Create base query builder
     *
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getBaseQuery(array $where, array $columns = ['id'], array $orderBy = []): Builder
    {
        $query = $this->query()->select($columns);

        $query = $this->scopeMakeWhere($query, $where);

        foreach ($orderBy as $key => $value) {
            $query->orderBy($key, $value);
        }
        
        if ($this->withTrashed) {
            /** @phpstan-ignore-next-line */
            $query->withTrashed();
        }

        return $query;
    }

    /**
     * Create where conditionals
     *
     * @param Builder $query
     * @param array $where
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Matmper\Exceptions\EmptyArrayDataException
     */
    private function scopeMakeWhere(Builder $query, array $where): Builder
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

        return $query;
    }
}
