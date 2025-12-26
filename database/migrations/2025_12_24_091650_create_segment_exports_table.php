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
        Schema::create('segment_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('segment_id')->constrained('segments')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade'); // admin who exported
            $table->string('file_path');        // path of exported CSV
            $table->json('filters_snapshot');   // snapshot of filters at export time
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('segment_exports');
    }
};
