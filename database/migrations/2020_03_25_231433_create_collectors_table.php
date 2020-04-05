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

			$table->char('initial-time-collect', 5);
			$table->char('final-time-collect', 5);
			$table->string('collection-interval', 5);
			$table->string('starting-address', 140)->nullable();
			$table->char('active', 1)->default('1');
			
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
