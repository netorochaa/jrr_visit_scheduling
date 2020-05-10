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
		Schema::create('cancellationTypes', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name', 140);
			$table->char('active', 3)->default('on');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('cancellationTypes');
	}
}
