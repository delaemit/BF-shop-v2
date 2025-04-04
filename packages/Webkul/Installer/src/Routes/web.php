<?php

declare(strict_types=1);

use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Webkul\Installer\Http\Controllers\InstallerController;

Route::middleware(['web', 'installer_locale'])->group(function (): void {
    Route::controller(InstallerController::class)->group(function (): void {
        Route::get('install', 'index')->name('installer.index');

        Route::middleware(StartSession::class)->prefix('install/api')->group(function (): void {
            Route::post('env-file-setup', 'envFileSetup')->name('installer.env_file_setup');

            Route::post('run-migration', 'runMigration')->name('installer.run_migration')->withoutMiddleware('web');

            Route::post('run-seeder', 'runSeeder')->name('installer.run_seeder')->withoutMiddleware('web');

            Route::get('download-sample', 'downloadSample')->name('installer.download_sample')->withoutMiddleware('web');

            Route::post('admin-config-setup', 'adminConfigSetup')->name('installer.admin_config_setup')->withoutMiddleware('web');

            Route::post('sample-products-setup', 'createSampleProducts')->name('installer.sample_products_setup')->withoutMiddleware('web');
        });
    });
});
