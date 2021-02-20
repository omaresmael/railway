<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->text('status')->default('available');

            $table->unsignedBigInteger('car_id');
            $table->foreign('car_id')
                ->references('id')
                ->on('cars')
                ->onDelete('cascade');

//            $table->unsignedBigInteger('trip_id');
//            $table->foreign('trip_id')
//                ->references('id')
//                ->on('trips');


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
        Schema::dropIfExists('seats');
    }
}
