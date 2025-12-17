<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('catalog_item_tag', function (Blueprint $table) {
            $table->foreignId('catalog_item_id')
                ->constrained('catalog_items')
                ->cascadeOnDelete();

            $table->foreignId('catalog_tag_id')
                ->constrained('catalog_tags')
                ->cascadeOnDelete();

            $table->primary(['catalog_item_id', 'catalog_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_item_tag');
    }
};
