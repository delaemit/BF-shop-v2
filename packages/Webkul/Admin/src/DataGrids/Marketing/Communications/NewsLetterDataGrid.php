<?php

declare(strict_types=1);

namespace Webkul\Admin\DataGrids\Marketing\Communications;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class NewsLetterDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('subscribers_list')
            ->select(
                'subscribers_list.id',
                'subscribers_list.is_subscribed as status',
                'subscribers_list.email'
            );

        $this->addFilter('status', 'subscribers_list.is_subscribed');

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
            'label' => trans('admin::app.marketing.communications.subscribers.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.marketing.communications.subscribers.index.datagrid.subscribed'),
            'type' => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
            'closure' => function ($value) {
                if ($value->status) {
                    return trans('admin::app.marketing.communications.subscribers.index.datagrid.true');
                }

                return trans('admin::app.marketing.communications.subscribers.index.datagrid.false');
            },
        ]);

        $this->addColumn([
            'index' => 'email',
            'label' => trans('admin::app.marketing.communications.subscribers.index.datagrid.email'),
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
        if (bouncer()->hasPermission('marketing.communications.subscribers.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('admin::app.marketing.communications.subscribers.index.datagrid.edit'),
                'method' => 'GET',
                'url' => fn($row) => route('admin.marketing.communications.subscribers.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('marketing.communications.subscribers.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.marketing.communications.subscribers.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn($row) => route('admin.marketing.communications.subscribers.delete', $row->id),
            ]);
        }
    }
}
