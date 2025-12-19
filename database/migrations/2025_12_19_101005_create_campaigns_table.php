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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title', 60);
            $table->string('subtitle', 120)->nullable();
            $table->string('image_url')->nullable();
            $table->string('cta_text', 30)->nullable();
            $table->string('cta_url')->nullable();
            $table->enum('status', ['draft', 'live', 'paused'])->default('draft');
            $table->boolean('requires_consent')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
