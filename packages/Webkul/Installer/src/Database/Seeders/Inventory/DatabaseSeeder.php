<?php

declare(strict_types=1);

namespace Webkul\Installer\Database\Seeders\Inventory;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param array $parameters
     *
     * @return void
     */
    public function run($parameters = []): void
    {
        $this->call(InventorySourceTableSeeder::class, false, ['parameters' => $parameters]);
    }
}
