<?php


namespace Core;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Core\Interfaces\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class EloquentRepository implements Repository
{
    protected Model|Builder $model;

    /**
     * Fin an item by id
     *
     * @param mixed $id
     *
     * @return Model|null
     */
    public function find(mixed $id, string $select = "*"): ?Model
    {
        return $this->model->select($select)->find($id);
    }

    public function findBy($column, $value = null, $select = "*", $relation = []): ?Model
    {
        $query = $this->model->select($select);
        if (is_array($column)) {
            $query->where($column);
        } else {
            $query->where($column, $value);
        }
        if (!empty($relation)) {
            $query->with($relation);
        }

        return $query->first();
    }

    /**
     * find Or Fail
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function findOrFail(mixed $id): mixed
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Return all items
     * @return Collection|null
     */
    public function all(
        string $select = "*",
        array  $filter = [],
        array  $orderBy = ['id', 'desc'],
        int    $limit = 20): ?\Illuminate\Database\Eloquent\Collection
    {
        $query = $this->model->select($select);
        $query->where($filter);
        $query->orderBy($orderBy[0], $orderBy[1]);

        return $query->get();
    }

    /**
     * Create an item
     *
     * @param array|mixed $data
     *
     * @return Model
     */
    public function create(mixed $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a item
     *
     * @param int|mixed   $id
     * @param array|mixed $data
     *
     * @return bool|mixed
     */
    public function update(mixed $id, array $data): mixed
    {
        return $this->model->findOrFail($id)->update($data);
    }

    /**
     * destroy many item with primary key
     *
     * @param array $id
     *
     * @return mixed
     */
    public function destroy(array $id): mixed
    {
        return $this->model->destroy($id);
    }

    /**
     * delete item
     *
     * @param int|Model $id
     *
     * @return mixed
     */
    public function delete(Model|int $id): mixed
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param array $data
     * @param array $uniqueBy
     *
     * @return int
     */
    public function upsert(array $data, array $uniqueBy = ['id'])
    {
        return $this->model->upsert($data, $uniqueBy);
    }

    public function getListingDataQueryWithFilter(Request $request): Builder
    {
        $query = $this->model->newQuery();
        if (!empty(trim($request->input('title')))) {
            $query->where(function ($subQuery) use ($request) {
                $subQuery->where('title_en', 'LIKE', '%' . $request->input('title') . '%')
                         ->orWhere('title_vi', 'LIKE', '%' . $request->input('title') . '%');
            });
        }

        return $query;
    }
}
