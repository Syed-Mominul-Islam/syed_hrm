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
        Schema::create('interpersonal_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key for users
            $table->string('skill_name'); // Name of the interpersonal skill
            $table->text('description')->nullable(); // Description of the skill
            $table->timestamps();

            // Foreign key constraint (if users table exists)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interpersonal_skills');
    }
};
