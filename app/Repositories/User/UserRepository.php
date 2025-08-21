<?php

namespace App\Repositories\User;

use Core\Interfaces\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface UserRepository extends Repository
{
    public function getListingDataQueryWithFilter(Request $request): Builder;
}
