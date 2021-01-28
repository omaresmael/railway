<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('train_id');
            $table->foreign('train_id')
                ->references('id')
                ->on('trains')
                ->onDelete('cascade');

            $table->unsignedBigInteger('level_id');
            $table->foreign('level_id')
                ->references('id')
                ->on('levels');


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
        Schema::dropIfExists('cars');
    }
}
