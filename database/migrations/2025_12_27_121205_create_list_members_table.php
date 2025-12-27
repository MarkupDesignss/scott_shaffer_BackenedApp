<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('list_members', function (Blueprint $table) {
            $table->id();

            $table->foreignId('list_id')
                  ->constrained('lists')
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->enum('status', ['invited', 'accepted'])
                  ->default('invited');

            $table->timestamps();

            $table->unique(['list_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('list_members');
    }
};