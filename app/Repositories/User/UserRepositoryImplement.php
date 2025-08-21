<?php

namespace App\Repositories\User;

use Carbon\Carbon;
use Core\EloquentRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Http\Request;

class UserRepositoryImplement extends EloquentRepository implements UserRepository
{
    protected Model|Builder $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getListingDataQueryWithFilter(Request $request): Builder
    {
        $query = $this->model->newQuery();
        if (!empty(trim($request->input('name')))) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }

        if (!empty(trim($request->input('email')))) {
            $query->where('email', $request->input('email'));
        }

        if (!empty(trim($request->input('status')))) {
            $query->where('status', $request->input('status'));
        }

        if (!empty(trim($request->input('created_at')))) {
            [$from, $to] = explode(' to ', $request->input('created_at'));

            try {
                $fromDate = Carbon::createFromFormat('d-m-Y', trim($from))->startOfDay();
                $toDate   = Carbon::createFromFormat('d-m-Y', trim($to))->endOfDay();

                $query->whereBetween('created_at', [$fromDate, $toDate]);
            } catch (\Exception $e) {
            }
        }

        return $query;
    }
}
