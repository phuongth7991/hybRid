<?php

namespace Core\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface Repository
{
    /**
     * Find an item by id
     *
     * @param mixed  $id
     * @param string $select
     *
     * @return Model|null
     */
    public function find(mixed $id, string $select = "*"): ?Model;

    public function findBy($column, $value, $select = "*", $relation = []): ?Model;

    /**
     * find or fail
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function findOrFail(mixed $id): mixed;

    /**
     * Return all items
     *
     * @param string   $select
     * @param array    $filter
     * @param string[] $orderBy
     * @param int      $limit
     *
     * @return Collection|null
     */
    public function all(
        string $select = "*",
        array  $filter = [],
        array  $orderBy = ['id', 'desc'],
        int    $limit = 20
    ): ?Collection;

    /**
     * Create an item
     *
     * @param array|mixed $data
     *
     * @return Model
     */
    public function create(mixed $data): Model;

    /**
     * Update a model
     *
     * @param int|mixed   $id
     * @param array|mixed $data
     *
     * @return bool|mixed
     */
    public function update(mixed $id, array $data): mixed;

    /**
     * Delete a model
     *
     * @param int|Model $id
     */
    public function delete(Model|int $id);

    /**
     * multiple delete
     *
     * @param array $id
     *
     * @return mixed
     */
    public function destroy(array $id): mixed;

    /**
     * Get model
     *
     * @return Model
     */
    public function getModel(): Model;

    /**
     * Upsert data
     *
     * @return Builder
     */
    public function upsert(array $data, array $uniqueBy = ['id']);

    /**
     * Get query for listing data with filter
     *
     * @param Request $request
     *
     * @return Builder
     */
    public function getListingDataQueryWithFilter(Request $request): Builder;
}
