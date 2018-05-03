<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessTasksStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_tasks_status', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('copy_id')->unsigned();
            $table->integer('task_id')->unsigned();
            $table->dateTime('is_ready');
            $table->text('comment');

            $table->foreign('copy_id')->references('id')->on('process_copy');
            $table->foreign('task_id')->references('id')->on('process_tasks');
            $table->unique('copy_id', 'task_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_tasks_status');
    }
}
