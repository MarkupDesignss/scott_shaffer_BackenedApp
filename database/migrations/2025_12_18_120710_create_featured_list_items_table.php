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
        Schema::create('featured_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('featured_list_id')
                ->constrained('featured_lists')
                ->cascadeOnDelete();
            $table->foreignId('catalog_item_id')
                ->constrained('catalog_items')
                ->cascadeOnDelete();
            // Ranking inside list
            $table->unsignedInteger('position');
            $table->timestamps();
            // Prevent duplicate item in same list
            $table->unique(['featured_list_id', 'catalog_item_id']);
            $table->index(['featured_list_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_list_items');
    }
};
