<?php

namespace App\Admin\User;

use Core\AbstractDataTable;
use Yajra\DataTables\Html\Column;

class UserDataTable extends AbstractDataTable
{
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('Id'),
            $this->avatarRecord(),
            Column::make('name')->title(__('Tên')),
            Column::make('email')->title(__('Email')),
            Column::make('phone')->title(__('SĐT')),
            $this->toggleColumn('users', 'status', __('Trạng thái'), __('Hoạt động'), __('Không hoạt động')),
            $this->date(),
        ];
    }
}
