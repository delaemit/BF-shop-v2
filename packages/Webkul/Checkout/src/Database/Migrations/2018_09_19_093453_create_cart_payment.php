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
        Schema::create('cart_payment', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('method');
            $table->string('method_title')->nullable();
            $table->integer('cart_id')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('cart_id')->references('id')->on('cart')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_payment');
    }
};
