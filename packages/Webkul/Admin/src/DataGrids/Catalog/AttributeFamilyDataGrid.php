<?php

declare(strict_types=1);

namespace Webkul\Admin\DataGrids\Catalog;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class AttributeFamilyDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        return DB::table('attribute_families')
            ->select(
                'id',
                'code',
                'name',
            );
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.catalog.families.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'code',
            'label' => trans('admin::app.catalog.families.index.datagrid.code'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.catalog.families.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('catalog.families.edit')) {
            $this->addAction([
                'icon' => 'icon-edit',
                'title' => trans('admin::app.catalog.families.index.datagrid.catalog.families.index.datagrid.edit'),
                'method' => 'GET',
                'url' => fn($row) => route('admin.catalog.families.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('catalog.families.edit')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => trans('admin::app.catalog.families.index.datagrid.catalog.families.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn($row) => route('admin.catalog.families.delete', $row->id),
            ]);
        }
    }
}
