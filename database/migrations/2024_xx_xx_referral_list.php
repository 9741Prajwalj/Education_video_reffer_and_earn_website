<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('referral_list', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('referral_name');
            $table->string('referral_phone');
            $table->bigInteger('user_id')->unsigned(); // Add user_id column as a foreign key
            $table->timestamps();
            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // Primary Key (unsignedBigInteger)
            $table->string('title'); // Notification Title
            $table->text('message'); // Notification Message
            $table->bigInteger('user_id')->unsigned(); // Add user_id column as a foreign key
            $table->timestamp('seen_at')->nullable();
            $table->timestamps(); // Created and Updated timestamps

            // Foreign Key constraint for user_id referencing the users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('otp');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('expires_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('referral_list');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('otps');
    }
};