<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = [], array $conditions = []): Collection
    {
        return $this->model->with($relations)->where($conditions)->get($columns);
    }

    /**
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function withCount(array $columns = ['*'], array $relations = [], array $conditions = []): Collection
    {
        return $this->model->withCount($relations)->where($conditions)->get($columns);
    }

    /**
     * @param array $columns
     * @param array $relations
     * @param integer $paginate
     * @return Collection
     */
    public function paginate(array $columns = ['*'], array $relations = [], array $conditions = [], $paginate = PAGINATE_10)
    {
        return $this->model->select($columns)->with($relations)->where($conditions)->paginate($paginate);
    }

    /**
     * @param array $columns
     * @param array $relations
     * @param integer $paginate
     * @return Collection
     */
    public function simplePaginate(array $columns = ['*'], array $relations = [], array $conditions = [], $paginate = PAGINATE_10)
    {
        return $this->model->select($columns)->with($relations)->where($conditions)->simplePaginate($paginate);
    }

    /**
     * Get all trashed models.
     *
     * @return Collection
     */
    public function allTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    /**
     * Find model by id.
     *
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model
     */
    public function findById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = [],
        array $conditions = []
    ): ?Model {
        return $this->model->select($columns)->with($relations)->where($conditions)->findOrFail($modelId)->append($appends);
    }

    /**
     * Find model by slug.
     *
     * @param int $modelSlug
     * @param array $columns
     * @param array $relations
     * @return Model
     */
    public function findBySlug(
        string $modelSlug,
        array $columns = ['*'],
        array $relations = [],
        array $conditions = []
    ): ?Model {
        return $this->model->select($columns)
            ->with($relations)
            ->where('slug', $modelSlug)
            ->where($conditions)
            ->first();
    }

    /**
     * Find trashed model by id.
     *
     * @param int $modelId
     * @return Model
     */
    public function findTrashedById(int $modelId): ?Model
    {
        return $this->model->withTrashed()->findOrFail($modelId);
    }

    /**
     * Find only trashed model by id.
     *
     * @param int $modelId
     * @return Model
     */
    public function findOnlyTrashedById(int $modelId): ?Model
    {
        return $this->model->onlyTrashed()->findOrFail($modelId);
    }

    /**
     * Create a model.
     *
     * @param array $payload
     * @return Model
     */
    public function create(array $payload): ?Model
    {
        $model = $this->model->create($payload);

        return $model->fresh();
    }

    /**
     * Update existing model.
     *
     * @param int $modelId
     * @param array $payload
     */
    public function update(int $modelId, array $payload)
    {
        $model = $this->findById($modelId)->update($payload);
        if($model) return $this->findById($modelId);
        return false;
    }

    /**
     * Update or create model.
     *
     * @param array $modelId
     * @param array $payload
     * @return object
     */
    public function updateOrCreate(array $modelId, array $payload)
    {
        if (isset($payload['password'])) {
            $payload['password'] = bcrypt($payload['password']);
        }

        return $this->model->updateOrCreate($modelId, $payload);
    }

    /**
     * Delete model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function deleteById(int $modelId): bool
    {
        return $this->findById($modelId)->delete();
    }

    /**
     * Restore model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function restoreById(int $modelId): bool
    {
        return $this->findOnlyTrashedById($modelId)->restore();
    }

    /**
     * Permanently delete model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function permanentlyDeleteById(int $modelId): bool
    {
        return $this->findTrashedById($modelId)->forceDelete();
    }
}
