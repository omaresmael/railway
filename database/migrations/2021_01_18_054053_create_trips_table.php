<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->time('depart_time');
            $table->time('arrival_time');

            $table->string('status')->default('current');

            $table->unsignedBigInteger('base_id');
            $table->foreign('base_id')
                ->references('id')
                ->on('stations');

            $table->unsignedBigInteger('destination_id');
            $table->foreign('destination_id')
                ->references('id')
                ->on('stations');

            $table->string('distance');

            $table->unsignedBigInteger('train_id');
            $table->foreign('train_id')
                ->references('id')
                ->on('trains');

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
        Schema::dropIfExists('trips');
    }
}
