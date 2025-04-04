<?php

declare(strict_types=1);

use Webkul\DataGrid\DataGrid;
use Webkul\DataGrid\Exceptions\InvalidDataGridException;

if (!function_exists('datagrid')) {
    /**
     * Datagrid helper.
     *
     * @param string $datagridClass
     */
    function datagrid(string $datagridClass): DataGrid
    {
        if (!is_subclass_of($datagridClass, DataGrid::class)) {
            throw new InvalidDataGridException("'{$datagridClass}' must extend the '" . DataGrid::class . "' class.");
        }

        return app($datagridClass);
    }
}
