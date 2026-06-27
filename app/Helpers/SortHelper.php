<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class SortHelper
{
    /**
     * Apply sorting to a query builder based on request parameters.
     *
     * @param Builder $query
     * @param array $sortableColumns  Whitelist of sortable column names ['column_name', ...] or ['alias' => 'db_column']
     * @param string $defaultSort    Default sort column
     * @param string $defaultDirection  'asc' or 'desc'
     * @return Builder
     */
    public static function apply(Builder $query, array $sortableColumns, string $defaultSort = 'id', string $defaultDirection = 'desc'): Builder
    {
        $sort = request('sort', $defaultSort);
        $direction = strtolower(request('direction', $defaultDirection));

        // Validate direction
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : $defaultDirection;

        // Check if column exists in whitelist (numeric array = plain column names)
        if (in_array($sort, $sortableColumns)) {
            return $query->orderBy($sort, $direction);
        }

        // Check if it's an aliased column (associative array)
        if (isset($sortableColumns[$sort])) {
            return $query->orderBy($sortableColumns[$sort], $direction);
        }

        // Fallback to default
        return $query->orderBy($defaultSort, $defaultDirection);
    }

    /**
     * Get the current sort state for use in views.
     *
     * @param string $field
     * @return array ['url' => string, 'icon' => string, 'is_sorted' => bool, 'direction' => string|null]
     */
    public static function state(string $field): array
    {
        $currentSort = request('sort');
        $currentDirection = request('direction');
        $isSorted = $currentSort === $field;

        if ($isSorted) {
            $newDirection = $currentDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $newDirection = 'asc';
        }

        $params = array_merge(request()->except(['sort', 'direction', 'page']), [
            'sort' => $field,
            'direction' => $newDirection,
        ]);

        $url = url()->current() . '?' . http_build_query($params);

        if ($isSorted) {
            $icon = $currentDirection === 'asc' ? 'ti-sort-ascending' : 'ti-sort-descending';
        } else {
            $icon = 'ti-arrows-sort';
        }

        return [
            'url' => $url,
            'icon' => $icon,
            'is_sorted' => $isSorted,
            'direction' => $isSorted ? $currentDirection : null,
        ];
    }
}