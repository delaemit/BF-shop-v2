<?php

declare(strict_types=1);

namespace Webkul\Admin\DataGrids\Marketing\Communications;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CampaignDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('marketing_campaigns')
            ->select(
                'id',
                'name',
                'subject',
                'status'
            );

        $this->addFilter('status', 'marketing_campaigns.status');

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
            'label' => trans('admin::app.marketing.communications.campaigns.index.datagrid.id'),
            'type' => 'integer',
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.marketing.communications.campaigns.index.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'subject',
            'label' => trans('admin::app.marketing.communications.campaigns.index.datagrid.subject'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.marketing.communications.campaigns.index.datagrid.status'),
            'type' => 'boolean',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'filterable_options' => [
                [
                    'label' => trans('admin::app.marketing.communications.campaigns.index.datagrid.active'),
                    'value' => 1,
                ],
                [
                    'label' => trans('admin::app.marketing.communications.campaigns.index.datagrid.inactive'),
                    'value' => 0,
                ],
            ],
            'closure' => function ($value) {
                if ($value->status) {
                    return trans('admin::app.marketing.communications.campaigns.index.datagrid.active');
                }

                return trans('admin::app.marketing.communications.campaigns.index.datagrid.inactive');
            },
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('marketing.communications.campaigns.edit')) {
            $this->addAction([
                'icon' => 'icon-edit',
                'title' => trans('admin::app.marketing.communications.campaigns.index.datagrid.edit'),
                'method' => 'GET',
                'url' => fn($row) => route('admin.marketing.communications.campaigns.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('marketing.communications.campaigns.delete')) {
            $this->addAction([
                'icon' => 'icon-delete',
                'title' => trans('admin::app.marketing.communications.campaigns.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn($row) => route('admin.marketing.communications.campaigns.delete', $row->id),
            ]);
        }
    }
}
