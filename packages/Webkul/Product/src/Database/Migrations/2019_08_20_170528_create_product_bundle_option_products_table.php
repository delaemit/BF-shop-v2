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
        Schema::create('product_bundle_option_products', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('product_bundle_option_id')->unsigned();
            $table->integer('qty')->default(0);
            $table->boolean('is_user_defined')->default(1);
            $table->boolean('is_default')->default(0);
            $table->integer('sort_order')->default(0);

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_bundle_option_id', 'product_bundle_option_id_foreign')->references('id')->on('product_bundle_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('product_bundle_option_products');
    }
};
