<?php
namespace Core\Interfaces;

use Yajra\DataTables\Html\Column;

interface DataTable
{
    public function getColumns(): array;
    public function toggleColumn(string $table, string $column = 'active', $title = 'Trạng thái'): Column;
}
