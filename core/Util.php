<?php

namespace Core;

use Core\Exceptions\CoreException;
use Illuminate\Support\Facades\DB;

class Util
{
    /**
     * Update column active trong 1 bảng nào đó.
     * @throws \Exception
     */
    public function toggleActive($table, $id, $column = 'active'): bool
    {
        if (empty($table)) {
            throw new CoreException(__('Table can\'t not empty'));
        }

        $item = DB::table($table)->where('id', $id)->first();
        $item->$column = !$item->$column;
        return DB::table($table)->where('id', $id)->update((array) $item);
    }
}
