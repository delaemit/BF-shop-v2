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
        Schema::create('cms_page_translations', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('page_title');
            $table->string('url_key');
            $table->longtext('html_content')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('locale');
            $table->integer('cms_page_id')->unsigned();

            $table->unique(['cms_page_id', 'url_key', 'locale']);
            $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_page_translations');
    }
};
