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
        Schema::create('learning_interests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Assuming you have a users table
            $table->string('interest'); // Field to store learning interests
            $table->string('completed_course')->nullable(); // Field for completed courses
            $table->timestamps();

            // Foreign key constraint (if applicable)
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
        Schema::dropIfExists('learning_interests');
    }
};
