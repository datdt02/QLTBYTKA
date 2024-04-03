<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
interface BaseRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getModel();

    /**
     * @return string
     */
    public function getTable();

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);
    /**
     * @param array $attributes
     * @return bool
     */
    public function update(array $attributes) : bool;

    /**
     * Create a new model.
     *
     * @param Model|array $data
     * @param array $condition
     * @return false|Model
     */
    public function createOrUpdate($data, $condition = []);

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return mixed
     */
    public function all($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc');
    /**
     * @param $id
     * @return mixed
     */
    public function find($id);
    /**
     * @param $id
     * @return mixed
     */
    public function findOneOrFail($id);
    /**
     * @param array $condition
     * @param array $select
     * @return mixed
     */
    public function findBy(array $condition, array $select = ['*']);
    /**
     * @param array $condition
     * @param array $select
     * @return mixed
     */
    public function findOneBy(array $condition, array $select = ['*']);
    /**
     * @param array $data
     * @return mixed
     */
    public function findOneByOrFail(array $data);
    /**
     * @return bool
     */
    public function delete() : bool;

     /**
     * @param array $data
     * @param array $with
     * @return mixed
     */
    public function firstOrCreate(array $data, array $with = []);

    /**
     * @param array $condition
     * @return mixed
     */
    public function deleteBy(array $condition = []);
    /**
     * @param array $data
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateArrayResults(array $data, int $perPage = 50);

    /**
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|Collection|mixed
     */
    public function advancedGet(array $params = []);

    /**
     * @param array $condition
     */
    public function forceDelete(array $condition = []);

    /**
     * @param array $condition
     * @return mixed
     */
    public function restoreBy(array $condition = []);

    /**
     * Find a single entity by key value.
     *
     * @param array $condition
     * @param array $select
     * @return mixed
     */
    public function getFirstByWithTrash(array $condition = [], array $select = []);

}