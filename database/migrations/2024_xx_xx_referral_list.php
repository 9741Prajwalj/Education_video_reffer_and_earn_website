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
            // $table->enum('status', ['sent', 'not sent'])->default('not sent');// Add 'sent' column
            $table->timestamps();
            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // Primary Key (unsignedBigInteger)
            $table->string('title'); // Notification Title
            $table->text('message'); // Notification Message
            $table->string('image')->nullable(); // Image URL (nullable in case there's no image)
            $table->boolean('read')->default(false); // A flag to track if the notification has been read
            $table->unsignedBigInteger('user_id')->nullable(); // Foreign Key referencing the users table
            $table->timestamps(); // Created and Updated timestamps

            // Foreign Key constraint for user_id referencing the users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('referral_list');
        Schema::dropIfExists('notifications');
    }
};