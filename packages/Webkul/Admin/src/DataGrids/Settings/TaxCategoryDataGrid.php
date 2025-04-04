<?php

declare(strict_types=1);

namespace Webkul\Admin\DataGrids\Settings;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class TaxCategoryDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        return DB::table('tax_categories')
            ->select(
                'id',
                'name',
                'code'
            );
    }

    /**
     * Add Columns.
     *
     * @return void
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.settings.taxes.categories.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.settings.taxes.categories.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'code',
            'label' => trans('admin::app.settings.taxes.categories.index.datagrid.code'),
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
        if (bouncer()->hasPermission('settings.taxes.tax_categories.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('admin::app.settings.taxes.categories.index.datagrid.edit'),
                'method' => 'GET',
                'url' => fn($row) => route('admin.settings.taxes.categories.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('settings.taxes.tax_categories.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.settings.taxes.categories.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn($row) => route('admin.settings.taxes.categories.delete', $row->id),
            ]);
        }
    }
}
