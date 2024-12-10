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
            $table->string('referral')->nullable(); // Referral Code (Optional)
            $table->timestamps(); // Created and Updated timestamps
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
