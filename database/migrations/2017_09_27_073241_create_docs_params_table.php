<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocsParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs_params', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('doc_id')->unsigned();
            $table->boolean('visible')->default(true);
            $table->integer('position')->unsigned()->default(100);
            $table->string('key');
            $table->string('value');
            $table->timestamps();

            $table->foreign('doc_id')->references('id')->on('docs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docs_params');
    }
}

