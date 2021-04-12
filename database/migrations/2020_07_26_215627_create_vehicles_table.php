<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_owner_id');
            $table->string('maker');
            $table->string('model');
            $table->string('production_year');
            $table->string('vin');
            $table->string('license_plate_number'); 
            $table->string('engine_number')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('vehicle_owner_id')->references('id')->on('vehicle_owners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
