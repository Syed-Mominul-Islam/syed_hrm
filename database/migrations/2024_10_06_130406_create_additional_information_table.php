<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_information', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('user_id'); // Foreign Key for User
            $table->text('languages_known')->nullable(); // For storing languages
            $table->text('hobbies')->nullable(); // For storing hobbies
            $table->text('volunteer_work')->nullable(); // For volunteer work
            $table->timestamps(); // Created and updated timestamps

            // Define Foreign Key Constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_information');
    }
};
