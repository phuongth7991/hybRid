<?php

namespace App\Admin\Config;

use Core\AbstractDataTable;
use Yajra\DataTables\Html\Column;

class ConfigDataTable extends AbstractDataTable
{
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('Id'),
            Column::make('key')->title(__('Key')),
            Column::make('value')->title(__('Giá trị')),
        ];
    }
}
