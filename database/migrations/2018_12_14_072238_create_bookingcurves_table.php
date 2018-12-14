<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingcurvesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookingcurves', function (Blueprint $table) {
            //$table->increments('id');
            $table->string('ota',50);
            $table->string('reservation_no',100)->nullable();
            $table->datetime('reserved_date')->nullable();
            $table->datetime('checkin_date')->nullable();
            $table->datetime('checkout_date')->nullable();
            $table->integer('total_price')->nullable();
            $table->integer('number_of_night')->nullable();
            $table->integer('number_of_room')->nullable();
            $table->integer('number_of_adult')->nullable();
            $table->integer('number_of_children')->nullable();
            $table->string('room_type',50)->nullable();
            $table->string('plan_name',100)->nullable();
            $table->string('reserved_name',50)->nullable();
           
            $table->string('reserved_type',10)->nullable();
            $table->string('checkin_weekday',5)->nullable();
            $table->integer('checkin_year_month')->nullable();
            $table->string('week_number',5)->nullable();
            $table->string('reserved_year_month',5)->nullable();
            $table->integer('leadtime')->nullable();
            $table->string('leadtime_type',5)->nullable();
            $table->integer('number_of_reserved')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bookingcurves');
    }
}
