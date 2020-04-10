<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectorHasFreedaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collector_has_freedays', function (Blueprint $table) {
            $table->unsignedInteger('collector_id');
            $table->unsignedInteger('freedays_id');

            $table->primary(['collector_id', 'freedays_id']);

            //FK
            $table->foreign('collector_id')->references('id')->on('collectors');
            //FK
			$table->foreign('freedays_id')->references('id')->on('freedays');

            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('collector_has_freedays');
    }
}
