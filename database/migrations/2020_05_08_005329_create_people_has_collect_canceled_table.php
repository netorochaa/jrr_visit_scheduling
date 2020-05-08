<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleHasCollectCanceledTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people_has_collect_canceled', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('people_id');
            $table->unsignedInteger('collect_id');
            $table->char('starRating', 1)->nullable();
            $table->string('obsRating', 254)->nullable();
            $table->string('covenant', 45)->nullable();
            $table->string('exams', 254)->nullable();
            //FK
            $table->foreign('people_id')->references('id')->on('people');
            //FK
			$table->foreign('collect_id')->references('id')->on('collects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people_has_collect_canceled');
    }
}
