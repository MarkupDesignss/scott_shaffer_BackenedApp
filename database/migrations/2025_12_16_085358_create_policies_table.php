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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();

            // REQUIRED
            $table->string('name');
            // e.g. "Welcome Message", "Privacy Policy", "Data Security"

            $table->longText('description');
            // Policy ke under pura content (paragraphs, line breaks)

            // OPTIONAL (Recommended)
            $table->string('slug')->unique();
            $table->integer('order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->string('version')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
