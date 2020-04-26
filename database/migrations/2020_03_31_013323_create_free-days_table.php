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
		Schema::create('freeDays', function(Blueprint $table) {
			$table->increments('id');
			$table->char('type', 1);
			$table->string('name', 50);
			$table->string('dateRange', 50);
			$table->char('active', 3)->default('off');

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
		Schema::dropIfExists('freeDays');
	}
}
