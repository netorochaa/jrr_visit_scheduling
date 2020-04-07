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
			$table->string('displacementRate', 5)->default('0,00');
			$table->char('region', 1);
			$table->char('active', 3)->default('on');

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
		Schema::disableForeignKeyConstraints();
		Schema::drop('neighborhoods');
	}
}
