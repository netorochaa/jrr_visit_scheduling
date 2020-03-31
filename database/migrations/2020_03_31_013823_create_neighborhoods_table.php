<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateNeighborhoodsTable.
 */
class CreateNeighborhoodsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('neighborhoods', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name', 45);
			$table->string('displacement-rate', 5)->default('0,00');
			$table->integer('region', 1);
			$table->integer('active', 1)->default('1');

			//FK
			$table->unsignedInteger('city_id');
			$table->foreign('city_id')->references('id')->on('cities');

			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('neighborhoods');
	}
}
