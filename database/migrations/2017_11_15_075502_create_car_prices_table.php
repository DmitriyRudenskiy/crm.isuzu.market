<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_prices', function (Blueprint $table) {
            $table->integer('type_id')->unsigned();
            $table->integer('base_id')->unsigned();
            $table->integer('price');

            $table->foreign('type_id')->references('id')->on('car_types');
            $table->foreign('base_id')->references('id')->on('car_bases');

            $table->primary(['type_id', 'base_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_prices');
    }
}
