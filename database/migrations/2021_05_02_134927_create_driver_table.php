<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('jmbg');
            $table->string('bankAccount');
            $table->boolean('onStandby');
            $table->boolean('personalVehicle');
            $table->integer('balance');

            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('vehicle_id')->references('id')->on('vehicle');

            $table->timestamps('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver');
    }
}
