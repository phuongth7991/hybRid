<?php

namespace Core;

use Core\Exceptions\CoreException;
use Core\Interfaces\Admin;
use Core\Interfaces\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

abstract class AbstractAdmin implements Admin
{
    public string      $route            = '';
    public string      $routePath        = '';
    public string      $resourceParam    = '';
    public bool        $showPassword     = false;
    public bool        $formBuilder      = true;
    public bool        $dataTable        = true;
    public bool        $hideListAction   = false;
    public bool        $hideButtonAction = false;
    protected bool     $autoStoreFile    = false;
    public bool        $enableExport     = false;
    protected ?Request $request          = null;

    protected bool $isActEdit = true;
    protected bool $isActDel  = true;
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @throws CoreException
     */
    public function getForm(): string
    {
        throw new CoreException('The admin ' . $this->getAdminName() . ' need build form class. Please create or set $formBuilder = false');
    }

    abstract public function getAdminName(): string;

    public function getRepository(): Repository
    {
        return $this->repository;
    }

    public function getRouteName(): string
    {
        return $this->route;
    }

    public function getTransformer(): ?string
    {
        return null;
    }

    public function configFilter(): ?array
    {
        return null;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function storeFile(string $fieldName): string|bool
    {
        $request = $this->getRequest();
        if ($request->file($fieldName . '_' . 'prefix')) {
            $path = $this->request->file($fieldName . '_' . 'prefix')
                                  ->store('public/' . $this->createStoreFilePath($this->route));
            $this->request->merge([$fieldName => str_replace('public/', '', $path)]);

            return $path;
        }

        return '';
    }

    private function createStoreFilePath($path): string
    {
        return str_replace(['.', '-'], ['_', '_'], $path);
    }

    /**
     * @return bool
     */
    private function isAutoStoreFile(): bool
    {
        return $this->autoStoreFile;
    }

    public function automationStoreFile(int $id = 0): void
    {
        $item = null;
        if ($id > 0) {
            $item = $this->getRepository()->find($id);
        }
        if ($this->isAutoStoreFile()) {
            $request = $this->getRequest();
            $files   = $request->file();
            foreach ($files as $fieldName => $file) {
                $fieldName = str_replace('_prefix', '', $fieldName);
                if (!is_array($file)) {
                    if ($this->request->get($fieldName . "_remove") && $this->removeFile($item->$fieldName)) {
                        $this->request->merge([$fieldName => '']);
                    }
                    $path = $file->store('public/' . $this->createStoreFilePath($this->route));
                    $this->request->merge([$fieldName => str_replace('public/', '', $path)]);

                    if (!empty($path) && $item !== null) {
                        $this->removeFile($item->$fieldName);
                    }
                } else {
                    $files = [];
                    foreach ($file as $newFile) {
                        $_path   = $newFile->store('public/' . $this->createStoreFilePath($this->route));
                        $files[] = str_replace('public/', '', $_path);
                    }
                    $listRemove = [];
                    if ($this->request->get($fieldName . "_remove")) {
                        $listRemove = json_decode($this->request->get($fieldName . "_remove"), true);
                        if (!empty($listRemove)) {
                            foreach ($listRemove as $filePath) {
                                $this->removeFile($filePath);
                            }
                        }
                    }
                    if ($item !== null && !empty($item->$fieldName)) {
                        $diff  = array_diff($item->$fieldName, $listRemove);
                        $files = [...$diff, ...$files];
                    }
                    $this->request->merge([$fieldName => $files]);
                }
            }
        }
    }

    public function removeFile(?string $filePath): bool
    {
        if (!empty($filePath)) {
            return Storage::delete('public/' . $filePath);
        }

        return false;
    }

    /**
     * @throws CoreException
     */
    public function getDataTable(): string
    {
        throw new CoreException('The admin ' . $this->getAdminName() . ' need datatable class. Please create or set $dataTable = false');
    }

    public function datatables(Request $request): JsonResponse
    {
        return \DataTables::of($this->getRepository()->getModel()->query())->toJson();
    }

    public function create($data): ?Model
    {
        return $this->getRepository()->create($data);
    }

    public function update($id, $data): Model
    {
        $item = $this->getRepository()->find($id);
        if (!$item) {
            throw new ModelNotFoundException(__('Không tìm thấy bản ghi được yêu cầu'));
        }
        $item->fill($data);
        $item->save();

        return $item;
    }

    public function delete($id)
    {
        return $this->getRepository()->delete($id);
    }

    public function beforeCreate(): void
    {
        //Todo handle before create action
    }

    public function afterCreate(Model $entity): void
    {
        //Todo handle after create action
    }

    public function beforeUpdate(): void
    {
        //Todo handle before update action
    }

    public function afterUpdate(Model $entity): void
    {
        //Todo handle after update action
    }


    public function beforeCommit(): void
    {
        //Todo handle before commit action
    }

    public function afterCommit(Model $entity): void
    {
        //Todo handle after commit action
    }


    public function beforeDelete(Model $entity): void
    {
        // Todo handle before delete action
    }

    public function afterDelete(array $entity): void
    {
        // Todo handle after delete action
    }

    public function beforeEdit(Model $entity): Model
    {
        return $entity;
    }

    public function afterEdit(Model $entity): Model
    {
        return $entity;
    }

    public function getRoutePath(): string
    {
        if (!empty($this->routePath)) {
            return ltrim($this->routePath, '/');
        }

        return $this->route;
    }

    public function getResourceParam(): string
    {
        if (!empty($this->resourceParam)) {
            return $this->resourceParam;
        }

        return $this->route;
    }

    public function getPageActions(): array
    {
        return [];
    }


    public function export(Request $request): void
    {
        throw new \Exception('Export method Not implement');
    }

    public function getIsActEdit(): bool
    {
        return $this->isActEdit;
    }

    public function getIsActDel(): bool
    {
        return $this->isActDel;
    }

}
