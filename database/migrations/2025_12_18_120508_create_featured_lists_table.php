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
        Schema::create('featured_lists', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->foreignId('category_id')
                ->constrained('catalog_categories')
                ->cascadeOnDelete();
            // Top-N logic
            $table->unsignedTinyInteger('list_size'); // 3 / 5 / 10
            // Draft / Live
            $table->enum('status', ['draft', 'live'])->default('draft');
            // Home screen ordering
            $table->unsignedInteger('display_order')->default(0);
            // Admin who created
            $table->foreignId('created_by')
                ->constrained('admins')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->index(['status', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_lists');
    }
};
