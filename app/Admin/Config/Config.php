<?php

namespace App\Admin\Config;

use Core\AbstractAdmin;
use App\Repositories\Config\ConfigRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Config extends AbstractAdmin implements ConfigAdmin
{

    protected ConfigRepository $repository;

    public string $route = 'config';

    public bool $hideListAction = true;

    protected bool $isActDel      = false;
    protected bool $autoStoreFile = true;

    public function __construct(ConfigRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getForm(): string
    {
        return ConfigForm::class;
    }

    public function getDataTable(): string
    {
        return ConfigDataTable::class;
    }

    public function getAdminName(): string
    {
        return __('cấu hình');
    }

    public function datatables(Request $request): JsonResponse
    {
        return \DataTables::eloquent($this->repository->getModel()->query())
                          ->toJson();
    }

    public function afterUpdate(Model $entity): void
    {
        Cache::forget('system_config');
    }
}
