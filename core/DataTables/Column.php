<?php

namespace Core\DataTables;

use Yajra\DataTables\Html\Column as BaseColumn;

class Column extends BaseColumn
{
    protected bool $isDate = false;
    protected string $dateFormat = 'd/m/Y h:i a';

    public function date(): static
    {
        $this->isDate = true;
        return $this;
    }
}
