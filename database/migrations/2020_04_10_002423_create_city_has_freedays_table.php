<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCityHasFreedaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city_has_freedays', function (Blueprint $table) {
            $table->unsignedInteger('city_id');
            $table->unsignedInteger('freedays_id');

            $table->primary(['city_id', 'freedays_id']);

            //FK
            $table->foreign('city_id')->references('id')->on('cities');
            //FK
			$table->foreign('freedays_id')->references('id')->on('freedays');

            $table->timestampsTz();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('city_has_freedays');
    }
}
