<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->id()->autoIncrement();

            /*location information*/
            $table->string('start_loc_longitude');
            $table->string('start_loc_latitude');
            $table->string('end_loc_longitude')->nullable();
            $table->string('end_loc_latitude')->nullable();
            
            /*foreign keys*/
            $table->foreignId('driver_id')->constrained();
            $table->foreignId('client_id')->constrained();

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
        Schema::dropIfExists('rides');
    }
}
