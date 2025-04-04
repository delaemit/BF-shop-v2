<?php

declare(strict_types=1);

namespace Webkul\Admin\DataGrids\Customers;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class GroupDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        return DB::table('customer_groups')
            ->select(
                'id',
                'code',
                'name'
            );
    }

    /**
     * Prepare columns.
     *
     * @return void
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.customers.groups.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'code',
            'label' => trans('admin::app.customers.groups.index.datagrid.code'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.customers.groups.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('customers.groups.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('admin::app.customers.groups.index.datagrid.edit'),
                'method' => 'PUT',
                'url' => fn($row) => '',
            ]);
        }

        if (bouncer()->hasPermission('customers.groups.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.customers.groups.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn($row) => route('admin.customers.groups.delete', $row->id),
            ]);
        }
    }
}
