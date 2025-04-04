<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tax_categories_tax_rates', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('tax_category_id')->unsigned();
            $table->integer('tax_rate_id')->unsigned();
            $table->timestamps();

            $table->unique(['tax_category_id', 'tax_rate_id'], 'tax_map_index_unique');
            $table->foreign('tax_category_id')->references('id')->on('tax_categories')->onDelete('cascade');
            $table->foreign('tax_rate_id')->references('id')->on('tax_rates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_categories_tax_rates');
    }
};
