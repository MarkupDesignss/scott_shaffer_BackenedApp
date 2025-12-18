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
        Schema::create('lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title', 80);
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->tinyInteger('list_size')->default(5);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->enum('visibility', ['private', 'public'])->default('private');
            $table->foreignId('cloned_from_id')->nullable()->constrained('lists')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lists');
    }
};
