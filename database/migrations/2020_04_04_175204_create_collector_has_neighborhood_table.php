<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectorHasNeighborhoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collector_has_neighborhood', function (Blueprint $table) {
			$table->unsignedInteger('neighborhood_id');
            $table->unsignedInteger('collector_id');

            $table->primary(['neighborhood_id', 'collector_id']);

            //FK
            $table->foreign('neighborhood_id')->references('id')->on('neighborhoods');
            //FK
			$table->foreign('collector_id')->references('id')->on('collectors');

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
        Schema::dropIfExists('collector_has_neighborhood');
    }
}
