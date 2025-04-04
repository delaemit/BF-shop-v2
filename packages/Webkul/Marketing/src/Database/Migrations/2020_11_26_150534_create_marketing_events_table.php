<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('marketing_events', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
        });

        /*
         * To Do (@devansh-webkul)
         *
         * - Should be in the seeder.
         */
        DB::table('marketing_events')->insert([
            'name' => 'Birthday',
            'description' => 'Birthday',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_events');
    }
};
