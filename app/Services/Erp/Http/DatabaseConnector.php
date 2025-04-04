<?php

declare(strict_types=1);

namespace App\Services\Erp\Http;

use Illuminate\Container\Attributes\DB;
use Illuminate\Database\ConnectionInterface;

readonly class DatabaseConnector
{
    /**
     * @param \Illuminate\Database\PostgresConnection $connection
     */
    public function __construct(
        #[DB('erp')]
        private ConnectionInterface $connection
    ) {
    }

    public function table(string $table): \Illuminate\Contracts\Database\Query\Builder
    {
        // $tables = [
        //     'attribute_product',
        //     'attributes',
        //     'collections',
        //     'exports',
        //     'failed_import_rows',
        //     'groups',
        //     'imports',
        //     'marketplace_tokens',
        //     'marketplaces',
        //     'media',
        //     'product_marketplace',
        //     'product_size',
        //     'products',
        //     'reports',
        //     'sizes',
        //     'types',
        //     'users',
        // ];

        if (!in_array($table, $this->connection->getSchemaBuilder()->getTableListing(), true)) {
            throw new \InvalidArgumentException('Unprocessable table name');
        }

        return $this->connection->table($table);
    }
}
