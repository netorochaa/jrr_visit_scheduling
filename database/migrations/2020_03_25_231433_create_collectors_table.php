<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCollectorsTable.
 */
class CreateCollectorsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('collectors', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 50);

			$table->char('initialTimeCollect', 5);
			$table->char('finalTimeCollect', 5);
			$table->string('collectionInterval', 5);
			$table->string('startingAddress', 140)->nullable();
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
		Schema::drop('collectors');
	}
}
