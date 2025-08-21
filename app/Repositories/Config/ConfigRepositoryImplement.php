<?php

namespace App\Repositories\Config;

use Core\EloquentRepository;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder;
use App\Models\Config;

class ConfigRepositoryImplement extends EloquentRepository implements ConfigRepository {


    protected Model | Builder $model;

    public function __construct(Config $model)
    {
        $this->model = $model;
    }
}
