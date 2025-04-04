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
        Schema::create('product_review_images', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('review_id')->unsigned();
            $table->string('type')->nullable();
            $table->string('path');

            $table->foreign('review_id')->references('id')->on('product_reviews')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('product_review_images');
    }
};
