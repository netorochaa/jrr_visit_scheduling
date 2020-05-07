<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateActivitiesTable.
 */
class CreateActivitiesTable extends Migration
{
	
	public function up()
	{
		Schema::create('activities', function(Blueprint $table) {
			$table->increments('id');
			$table->char('status');
			$table->string('reasonCancellation', 254)->nullable();
			$table->datetime('start');
			$table->datetime('end')->nullable();

            $table->unsignedInteger('collector_id');
			$table->foreign('collector_id')->references('id')->on('collectors');

			$table->unsignedInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');

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
		Schema::drop('activities');
	}
}
