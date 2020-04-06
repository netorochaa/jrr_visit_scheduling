<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateFreeDaysTable.
 */
class CreateFreeDaysTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('free-days', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 50);
			$table->string('initial-date', 50);
			$table->string('final-date', 50);
			$table->char('active', 3)->default('on');

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
		Schema::drop('free-days');
	}
}
