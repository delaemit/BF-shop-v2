<?php

declare(strict_types=1);

namespace Webkul\Installer\Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
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
        DB::table('countries')->delete();

        $countries = json_decode(file_get_contents(__DIR__ . '/../../../Data/countries.json'), true);

        DB::table('countries')->insert($countries);
    }
}
