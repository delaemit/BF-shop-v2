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
        Schema::create('product_customer_group_prices', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->integer('qty')->default(0);
            $table->string('value_type');
            $table->decimal('value', 12, 4)->default(0);
            $table->integer('product_id')->unsigned();
            $table->integer('customer_group_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('product_customer_group_prices');
    }
};
