<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class SchemaHelper
{
    /**
     * Check if a column exists in a table.
     *
     * Uses SHOW COLUMNS which is compatible with all MySQL/MariaDB versions,
     * unlike Schema::hasColumn() which queries the `generation_expression`
     * column from information_schema (only available in MariaDB >= 10.2.1).
     *
     * @param  string  $table
     * @param  string  $column
     * @return bool
     */
    public static function hasColumn(string $table, string $column): bool
    {
        $columns = DB::select("SHOW COLUMNS FROM `{$table}`");

        foreach ($columns as $col) {
            if ($col->Field === $column) {
                return true;
            }
        }

        return false;
    }
}