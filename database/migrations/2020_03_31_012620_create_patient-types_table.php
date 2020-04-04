<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePatientTypesTable.
 */
class CreatePatientTypesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('patient-types', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name', 45);
			$table->char('need-reponsible', 1)->default('1');
			$table->char('priority', 1)->default('1');
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
		Schema::drop('patient-types');
	}
}
