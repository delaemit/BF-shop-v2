<?php

declare(strict_types=1);

namespace Webkul\Admin\DataGrids\Marketing\SearchSEO;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\DataGrid\DataGrid;

class SitemapDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        return DB::table('sitemaps')
            ->addSelect(
                'id',
                'file_name',
                'path',
                'path as url'
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
            'label' => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.id'),
            'type' => 'integer',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'file_name',
            'label' => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.file-name'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'path',
            'label' => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.path'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'url',
            'label' => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.link-for-google'),
            'type' => 'string',
            'closure' => fn($row) => Storage::url(clean_path($row->path . '/' . $row->file_name)),
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('marketing.sitemaps.edit')) {
            $this->addAction([
                'index' => 'edit',
                'icon' => 'icon-edit',
                'title' => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.edit'),
                'method' => 'GET',
                'route' => 'admin.marketing.search_seo.sitemaps.update',
                'url' => fn($row) => route('admin.marketing.search_seo.sitemaps.update', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('marketing.sitemaps.delete')) {
            $this->addAction([
                'index' => 'delete',
                'icon' => 'icon-delete',
                'title' => trans('admin::app.marketing.search-seo.sitemaps.index.datagrid.delete'),
                'method' => 'DELETE',
                'url' => fn($row) => route('admin.marketing.search_seo.sitemaps.delete', $row->id),
            ]);
        }
    }
}
