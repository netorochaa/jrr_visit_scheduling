<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCancellationTypesTable.
 */
class CreateCancellationTypesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cancellation-types', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name', 140);
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
		Schema::drop('cancellation-types');
	}
}
