<?php

namespace Core\Interfaces;

use Illuminate\Support\Collection;

interface Service
{
    public function find(int $id): mixed;
    public function all($select = "*", array $filter = [], array $orderBy = ['id','desc']);

    public function paginate($select, $filter, $orderBy, $limit);
}
