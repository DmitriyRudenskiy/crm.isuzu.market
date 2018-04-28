<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessCopyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_copy', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_ready')->default(false);
            $table->integer('process_id')->unsigned();
            $table->string('name');
            $table->timestamps();

            $table->foreign('process_id')->references('id')->on('process');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_copy');
    }
}

