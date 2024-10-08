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
        Schema::create('other_qualification', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // assuming user_id references the users table
            $table->string('qualification_name');
            $table->year('passing_year');
            $table->boolean('is_delete')->default(0); // default value set to 0
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('other_qualification');
    }
};
