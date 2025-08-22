<?php

namespace App\Admin\User;

use App\Enums\UserEnum;
use Core\AbstractAdmin;
use App\Repositories\User\UserRepository;
use Core\DataFilter\Field;
use DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class User extends AbstractAdmin implements UserAdmin
{

    /**
     * don't change $this->repository variable name
     * because used in extends service class
     */
    protected ?UserRepository $repository;

    public string $route = 'user';
    protected bool $autoStoreFile = true;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getForm(): string
    {
        return UserForm::class;
    }

    public function getDataTable(): string
    {
        return UserDataTable::class;
    }

    public function getAdminName(): string
    {
        return __('người dùng');
    }

    public function configFilter(): ?array
    {
        return [
            'name'       => [
                'type'        => Field::TEXT,
                'label'       => __('Tên'),
                'placeholder' => __('Vui lòng nhập tên...')
            ],
            'email'      => [
                'type'        => Field::EMAIL,
                'label'       => __('Email'),
                'placeholder' => __('Vui lòng nhập email...')
            ],
            'status'     => [
                'type'    => Field::SELECT,
                'label'   => __('Trạng thái'),
                'choices' => UserEnum::getStatus()
            ],
            'created_at' => [
                'type'  => Field::DATE_RANGE_PICKER,
                'label' => __('Ngày tạo'),
                'placeholder' => __('Vui lòng chọn ngày'),
            ],
        ];
    }

    public function beforeCreate(): void
    {
        $this->_prepareRequest();
    }

    public function beforeUpdate(): void
    {
        $this->_prepareRequest();
    }

    public function datatables(Request $request): JsonResponse
    {
        return DataTables::eloquent($this->repository->getListingDataQueryWithFilter($request))->toJson();
    }

    private function _prepareRequest(): void
    {
        $request  = $this->getRequest();
        $password = $request->input('password');
        if (!empty($password)) {
            $request->merge(
                [
                    'password' => bcrypt($password),
                ]
            );
        }else {
            $request->request->remove('password');
        }
        $this->setRequest($request);
    }
}
