<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestbookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testbookings', function (Blueprint $table) {
            //$table->increments('id');
            $table->string('ota',50);
            $table->datetime('reserved_date')->nullable();
            $table->datetime('checkin_date')->nullable();
            $table->integer('total_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('testbookings');
    }
}
