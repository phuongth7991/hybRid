<?php

namespace Core\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface Admin
{
    public function getForm();

    public function datatables(Request $request);

    public function getRepository(): Repository;

    public function getRouteName(): string;

    public function setRequest(Request $request);

    public function storeFile(string $fieldName): mixed;

    public function automationStoreFile(int $id = 0): void;

    public function getRequest(): Request;

    public function getAdminName(): string;

    public function getDataTable(): string;

    public function create($data): ?\Illuminate\Database\Eloquent\Model;

    public function update($id, $data): \Illuminate\Database\Eloquent\Model;

    public function getTransformer(): ?string;

    public function configFilter(): ?array;

    public function beforeCreate(): void;

    public function afterCreate(Model $entity): void;

    public function beforeUpdate(): void;

    public function afterCommit(Model $entity): void;

    public function beforeDelete(Model $entity): void;

    public function afterDelete(array $entity): void;

    public function beforeEdit(Model $entity): Model;

    public function afterEdit(Model $entity): Model;

    public function beforeCommit(): void;

    public function afterUpdate(Model $entity): void;

    public function getRoutePath(): string;

    public function getResourceParam(): string;

    public function getPageActions(): array;

    public function export(Request $request);

    public function getIsActEdit(): bool;

    public function getIsActDel(): bool;

}
