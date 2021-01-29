<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationTripTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station_trip', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('station_id');
            $table->foreign('station_id')
                ->references('id')
                ->on('stations');



            $table->unsignedBigInteger('trip_id');
            $table->foreign('trip_id')
                ->references('id')
                ->on('trips');
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
        Schema::dropIfExists('stations_trips');
    }
}
