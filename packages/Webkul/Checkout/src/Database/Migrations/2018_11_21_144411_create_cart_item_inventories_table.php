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
        Schema::create('cart_item_inventories', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('qty')->unsigned()->default(0);
            $table->integer('inventory_source_id')->unsigned()->nullable();
            $table->integer('cart_item_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_item_inventories');
    }
};
