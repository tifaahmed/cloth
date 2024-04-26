<?php

namespace App\Repository\Eloquent;

use App\Repository\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

use Torann\LaravelRepository\Repositories\AbstractRepository;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     * BaseRepository  constructor
     * @param  Model $model
     */
    public function __construct(Model $model)
    {
        $this->model =  $model;
    }

    /**
     * @param  array  $columns
     * @param  array  $relations
     * @return  Collection
     */
    public function all(int $limit = 999, array $columns = ['*'], array $relations = []): Collection
    {
        $keyName = $this->model->getKeyName();
        $fillable = $this->model->searchable;
        array_push($fillable, $keyName);
        $all_scopes = [];
        // dd(request()->get('filter'));
        if ($this->model->scopes) {
            foreach ($this->model->scopes as $key => $value) {
                array_push($all_scopes, AllowedFilter::scope($value));
            }
        }


        return QueryBuilder::for($this->model->with($relations))
            ->allowedFilters($fillable)
            ->allowedFilters($all_scopes)
            ->where('vendor_id', auth()->id())
            ->limit($limit)
            ->get($columns);
    }

    /**
     * @param  int  $modelId
     * @return  pagination
     */
    public function collection(int $modelId = 10, array $relations = [])
    {
        $fillable = $this->model->searchable;

        $all_scopes = [];
        if ($this->model->scopes) {
            foreach ($this->model->scopes as $key => $value) {
                array_push($all_scopes, AllowedFilter::scope($value));
            }
        }
        $all_searchable_exact = [];
        if ($this->model->searchable_exact) {
            foreach ($this->model->searchable_exact as $key => $value) {
                array_push($all_scopes, AllowedFilter::exact($value));
            }
        }

        return QueryBuilder::for($this->model->with($relations))
            ->allowedFilters($fillable)
            ->allowedFilters($all_scopes)
            ->allowedFilters($all_searchable_exact)
            ->where('vendor_id', auth()->id())
            ->latest('id')->paginate($modelId)->appends(request()->query());
    }

    public function repo_filter(array $relations = [])
    {
        $keyName = $this->model->getKeyName();
        $fillable = $this->model->getFillable();
        array_push($fillable, $keyName);
        $all_scopes = [];
        if ($this->model->scopes) {
            foreach ($this->model->scopes as $key => $value) {
                array_push($all_scopes, AllowedFilter::scope($value));
            }
        }
        return QueryBuilder::for($this->model->with($relations))
            ->allowedFilters($fillable)
            ->allowedFilters($all_scopes);
    }

    public function queryPaginate($itemsNumber)
    {
        return latest()->paginate($itemsNumber)->appends(request()->query());
    }

    /**
     * @param  int  $modelId
     * @return  pagination trashed
     */
    public function collection_trash(int $modelId, array $relations = [])
    {
        $keyName = $this->model->getKeyName();
        $fillable = $this->model->getFillable();

        array_push($fillable, $keyName);

        return QueryBuilder::for($this->model->with($relations))
            ->allowedFilters($fillable)
            ->onlyTrashed()->latest('id')->paginate($modelId)->appends(request()->query());
    }

    /**
     * get all trashed models
     * @return  Collection
     */
    public function allTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    public function deleteByRelation($relation_coulmn, int $modelId): bool
    {
        return $this->model->where($relation_coulmn, $modelId)->delete();
    }

    /**
     * find model by id
     * @param  int $modelId
     * @param  array $columns
     * @param  array $relations
     * @param  array $appends
     * @return Model
     */

    public function findById(
        int $modelId,
        array $columns   = ['*'],
        array $relations = [],
        array $appends   = []
    ): ?Model {
        return  $this->model->select($columns)->with($relations)->findOrFail($modelId)->append($appends);
    }

    /**
     * find trashed model by id
     * @param  int $modelId
     * @return Model
     */
    public function findTrashedById(int $modelId): ?Model
    {
        return  $this->model->withTrashed()->findOrFail($modelId);
    }

    /**
     * find trashed model by id
     * @param  int $modelId
     * @return Model
     */
    public function findOnlyTrashedById(int $modelId): ?Model
    {
        return  $this->model->onlyTrashed()->findOrFail($modelId);
    }

    /**
     * create a model
     * @param  array $payload
     * @return Model
     */
    public function create(array $payload): ?Model
    {
        $model = $this->model->create($payload);
        return  $model->fresh();
    }

    /**
     * firstOrCreate a model
     * @param  array $payload
     * @return Model
     */
    public function firstOrCreate(array $payload): ?Model
    {
        $model = $this->model->firstOrCreate($payload);
        return  $model->fresh();
    }

    /**
     * update  existing model
     * @param  int $modelId
     * @param  array $payload
     * @return bool
     */
    public function update(int $modelId, array $payload): bool
    {
        $model = $this->findById($modelId);
        return  $model->update($payload);
    }

    /**
     * delete model by id
     * @param  int $modelId
     * @return bool
     */
    public function deleteById(int $modelId): bool
    {
        return $this->findById($modelId)->delete();
    }

    /**
     * delete model by id
     * @param  int $modelId
     * @return bool
     */
    public function deleteByArray(array $modelIds = []): bool
    {
        return $this->model->whereIn('id', $modelIds)->delete();
    }

    /**
     * restor model by id
     * @param  int $modelId
     * @return bool
     */
    public function restorById(int $modelId): bool
    {
        return $this->findOnlyTrashedById($modelId)->restore();
    }

    /**
     * premanently delete model by id
     * @param  int $modelId
     * @return bool
     */
    public function PremanentlyDeleteById(int $modelId): bool
    {
        return $this->findOnlyTrashedById($modelId)->forceDelete();
    }
}
