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
        Schema::create('cart_rule_translations', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('locale');
            $table->text('label')->nullable();
            $table->integer('cart_rule_id')->unsigned();

            $table->unique(['cart_rule_id', 'locale']);
            $table->foreign('cart_rule_id')->references('id')->on('cart_rules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_rule_translations');
    }
};
