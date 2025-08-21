<?php

namespace Core;

use Core\Interfaces\Admin;
use Core\Interfaces\DataTable;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;

abstract class AbstractDataTable implements DataTable
{

    protected Admin   $admin;
    protected Builder $builder;
    protected array   $action = [];

    public function __construct(Builder $builder, Admin $admin)
    {
        $this->admin   = $admin;
        $this->builder = $builder;
    }

    public function build(Request $request): Builder
    {
        $columns = $this->admin->hideButtonAction
            ? $this->getColumns()
            : array_merge($this->getColumns(), $this->actionColumnButton());

        return $this->builder->columns($columns)
                             ->ajax(route($this->admin->getRouteName() . '.index') . '?' . $request->getQueryString());
    }

    public function money($column = 'price', $title = 'Giá gốc')
    {
        return Column::make($column)->title(__($title))
                     ->render('DataTableHelper.formatMoney(full, \'' . $column . '\')');
    }

    public function actionColumnButton(): array
    {
        return [
            Column::make('action')->title(__('Thao tác'))
                  ->render($this->renderButtonAction())
                  ->searchable(false)
                  ->orderable(false)
                  ->exportable(false)
                  ->printable(false)
        ];
    }

    private function renderButtonAction(): string
    {
        return 'function() {
            return renderBtnAction(this, "admin/' . $this->getRoutePath() . '", "' . $this->admin->getRouteName() . '", "' . $this->admin->getIsActEdit() . '", "' . $this->admin->getIsActDel() . '")
        }';
    }

    private function getRoutePath()
    {
        if (!empty($this->admin->getRoutePath()))
            return $this->admin->getRoutePath();

        return $this->admin->getRouteName();
    }

    abstract public function getColumns(): array;

    public function toggleColumn(string $table, string $column = 'active', $title = 'Trạng thái',
                                        $activeString = 'Kích hoạt', $deActiveString = 'Ngừng kích hoạt'): Column
    {
        return Column::make($column)->title(__($title))
                     ->render('DataTableHelper.active(full, \'' . $table . '\', \'' . $column . '\', \'' . $activeString . '\', \'' . $deActiveString . '\')');
    }

    public function toggleCheckIcon(string $column = 'active', $title = '#', $class = 'success'): Column
    {
        return Column::make($column)->title(__($title))
                     ->render('DataTableHelper.checkIcon(full, \'' . $column . '\', \'' . $class . '\')');
    }

    public function date($column = 'created_at', $title = 'Ngày tạo'): Column
    {
        return Column::make($column)->title(__($title))
                     ->render('DataTableHelper.formatDate(full, \'' . $column . '\')');
    }

    public function badge($column = 'name', $title = 'Ngày tạo', $class = 'info'): Column
    {
        return Column::make($column)->title(__($title))
                     ->render('DataTableHelper.badge(full, \'' . $column . '\', \'' . $class . '\')');
    }

    public function formatStatus($column = 'name', $title = 'Trạng thái', $listStatus = ''): Column
    {
        return Column::make($column)->title(__($title))
                     ->render('DataTableHelper.formatStatus(full, \'' . $column . '\', \'' . $listStatus . '\')');
    }

    public function html($column = 'name', $title = 'Trạng thái'): Column
    {
        return Column::make($column)->title(__($title))->render('DataTableHelper.html(full, \'' . $column . '\')');
    }

    public function link($column = 'name', $title = 'Đường dẫn', $toEdit = false, $toView = false,
                         $customPath = ''): Column
    {
        if (empty($customPath)) {
            $customPath = $this->getRoutePath();
        }
        if ($toEdit) {
            return Column::make($column)->title(__($title))
                         ->render('"<a href=\'/admin/' . $customPath . '/" + full.id + "/edit\' class=\'text-primary font-weight-bold text-hover-primary\' target=\'_blank\' title=\'' . __('Chỉnh sửa') . '\'>"+data+"</a>"');
        }
        if ($toView) {
            return Column::make($column)->title(__($title))
                         ->render('"<a href=\'/admin/' . $customPath . '/" + full.id + "/view\' class=\'text-primary font-weight-bold text-hover-primary\' target=\'_blank\' title=\'' . __('Chi tiết') . '\'>"+data+"</a>"');
        }

        return Column::make($column)->title(__($title))
                     ->render('"<a target=\'_blank\' href=\'//"+data+"\'>"+data+"</a>"');
    }
}
