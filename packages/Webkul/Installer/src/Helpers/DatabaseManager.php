<?php

declare(strict_types=1);

namespace Webkul\Installer\Helpers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\Installer\Database\Seeders\DatabaseSeeder as BagistoDatabaseSeeder;
use Webkul\Installer\Database\Seeders\ProductTableSeeder;

class DatabaseManager
{
    /**
     * Check Database Connection.
     */
    public function isInstalled()
    {
        if (!file_exists(base_path('.env'))) {
            return false;
        }

        try {
            DB::connection()->getPDO();

            $isConnected = (bool) DB::connection()->getDatabaseName();

            if (!$isConnected) {
                return false;
            }

            $hasTable = Schema::hasTable('admins');

            if (!$hasTable) {
                return false;
            }

            $userCount = DB::table('admins')->count();

            if (!$userCount) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Drop all the tables and migrate in the database
     *
     * @return string|void
     */
    public function migration()
    {
        try {
            Artisan::call('migrate:fresh');
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Seed the database.
     *
     * @param mixed $data
     *
     * @return string|void
     */
    public function seeder($data)
    {
        $data['parameter'] = [
            'default_locale' => $data['parameter']['default_locales'],
            'allowed_locales' => $data['parameter']['allowed_locales'],
            'default_currency' => $data['parameter']['default_currency'],
            'allowed_currencies' => $data['parameter']['allowed_currencies'],
        ];

        try {
            app(BagistoDatabaseSeeder::class)->run($data['parameter']);

            $this->storageLink();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Storage Link.
     */
    private function storageLink(): void
    {
        Artisan::call('storage:link');
    }

    /**
     * Generate New Application Key
     */
    public function generateKey(): void
    {
        try {
            Artisan::call('key:generate');
        } catch (\Exception $e) {
        }
    }

    /**
     * Generate fake product data.
     *
     * @param mixed $parameters
     *
     * @return string|void
     */
    public function seedSampleProducts($parameters)
    {
        try {
            app(ProductTableSeeder::class)->run($parameters);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
