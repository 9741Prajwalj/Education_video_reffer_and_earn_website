<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('username')->unique(); // Username
            $table->string('email')->unique(); // Email
            $table->string('phone_number')->unique(); // Phone Number
            $table->string('password'); // Password
            $table->integer('referral_count')->default(0); // Referral Count
            $table->integer('referral_received')->default(0); // Referral Received
            $table->integer('points')->default(5);
            $table->timestamps(); // Created and Updated timestamps
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('points');
        });
    }
};