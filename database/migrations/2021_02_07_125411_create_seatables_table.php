<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seatables', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('valid');

            $table->unsignedBigInteger('seat_id');
            $table->unsignedBigInteger('seatable_id');
            $table->string('seatable_type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seatables');
    }
}
