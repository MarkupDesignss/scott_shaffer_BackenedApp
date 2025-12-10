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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('country_code', 5);
            $table->string('phone')->unique();
            $table->string('country');
            $table->boolean('is_phone_verified')->default(false);
            $table->integer('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('password')->nullable();
            $table->boolean('status')->default(1)->comment('0 = Inactive, 1 = Active');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
