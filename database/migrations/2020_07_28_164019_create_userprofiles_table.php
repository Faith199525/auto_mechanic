<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserprofilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
        //Columns in the UserProfile: id, firstname, lastname, user_id, supervisor, phone,image,location (country,state & city)

            $table->id();
            $table->timestamps();
            $table->string('firstname');
            $table->string('lastname');
            $table->integer('user_id');
            $table->string('supervisor')->nullable();
            $table->string('phone');
            $table->string('image')->default('no_image.jpg');
            $table->json('location');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
