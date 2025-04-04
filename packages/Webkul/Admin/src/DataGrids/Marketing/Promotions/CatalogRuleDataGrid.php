<?php

declare(strict_types=1);

namespace Webkul\Admin\DataGrids\Marketing\Promotions;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CatalogRuleDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('catalog_rules')
            ->select(
                'catalog_rules.id',
                'name',
                'status',
                'starts_from',
                'ends_till',
                'sort_order'
            );

        $this->addFilter('status', 'status');

        return $queryBuilder;
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
            'label' => trans('admin::app.marketing.promotions.catalog-rules.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.marketing.promotions.catalog-rules.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'starts_from',
            'label' => trans('admin::app.marketing.promotions.catalog-rules.index.datagrid.start'),
            'type' => 'datetime',
            'filterable' => true,
            'filterable_type' => 'datetime_range',
            'sortable' => true,
            'closure' => fn($value) => $value->starts_from ?? '-',
        ]);

        $this->addColumn([
            'index' => 'ends_till',
            'label' => trans('admin::app.marketing.promotions.catalog-rules.index.datagrid.end'),
            'type' => 'datetime',
            'filterable' => true,
            'filterable_type' => 'datetime_range',
            'sortable' => true,
            'closure' => fn($value) => $value->ends_till ?? '-',
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.marketing.promotions.catalog-rules.index.datagrid.status'),
            'type' => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'filterable_options' => [
                [
                    'label' => trans('admin::app.marketing.promotions.catalog-rules.index.datagrid.active'),
                    'value' => 1,
                ],
                [
                    'label' => trans('admin::app.marketing.promotions.catalog-rules.index.datagrid.inactive'),
                    'value' => 0,
                ],
            ],
            'sortable' => true,
            'closure' => function ($value) {
                if ($value->status) {
                    return trans('admin::app.marketing.promotions.catalog-rules.index.datagrid.active');
                }

                return trans('admin::app.marketing.promotions.catalog-rules.index.datagrid.inactive');
            },
        ]);

        $this->addColumn([
            'index' => 'sort_order',
            'label' => trans('admin::app.marketing.promotions.catalog-rules.index.datagrid.priority'),
            'type' => 'integer',
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
        if (bouncer()->hasPermission('marketing.promotions.catalog_rules.edit')) {
            $this->addAction([
                'icon' => 'icon-edit',
                'title' => trans('admin::app.marketing.promotions.catalog-rules.index.datagrid.edit'),
                'method' => 'GET',
                'url' => fn($row) => route('admin.marketing.promotions.catalog_rules.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('marketing.promotions.catalog_rules.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => trans('admin::app.marketing.promotions.catalog-rules.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn($row) => route('admin.marketing.promotions.catalog_rules.delete', $row->id),
            ]);
        }
    }
}
