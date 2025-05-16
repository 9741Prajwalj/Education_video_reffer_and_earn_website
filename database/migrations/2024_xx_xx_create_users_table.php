<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Create users table
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('username')->unique(); // Username
            $table->string('email')->unique(); // Email
            $table->string('phone_number')->unique(); // Phone Number
            $table->string('password'); // Password
            $table->integer('referral_count')->default(0); // Referral Count
            $table->integer('referral_received')->default(0); // Referral Received
            $table->integer('points')->default(5); // Points
            $table->timestamps(); // Created and Updated timestamps
        });

        // Create password_reset_tokens table
        Schema::create('password_resets_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique(); // Email for verification
            $table->string('otp'); // OTP
            $table->string('token');
            $table->timestamp('expires_at'); // Expiration time
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Drop tables on rollback
        Schema::dropIfExists('password_resets_tokens');
        Schema::dropIfExists('users');
    }
};
