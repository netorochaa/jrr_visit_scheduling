<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePeopleTable.
 */
class CreatePeopleTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('people', function(Blueprint $table) {
			$table->increments('id');
			$table->char('ra', 10)->nullable();
			$table->string('name', 45);
			$table->string('fone', 45);
			$table->string('email', 45)->nullable();
			$table->string('otherFone', 45)->nullable();
			$table->char('typeResponsible', 3)->nullable();
			$table->string('nameResponsible', 45)->nullable();
			$table->string('foneResponsible', 45);
			$table->string('CPF', 45);
			$table->string('RG', 45)->nullable();
			$table->string('birth', 45);
			$table->string('covenant', 45);
			$table->string('exams', 254);
			$table->string('medication', 254)->nullable();

			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::disableForeignKeyConstraints();
		Schema::drop('people');
	}
}
