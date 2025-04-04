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
        Schema::create('cart_rule_customers', function (Blueprint $table): void {
            $table->increments('id');
            $table->bigInteger('times_used')->unsigned()->default(0);
            $table->integer('customer_id')->unsigned();
            $table->integer('cart_rule_id')->unsigned();

            $table->foreign('cart_rule_id')->references('id')->on('cart_rules')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_rule_customers');
    }
};
